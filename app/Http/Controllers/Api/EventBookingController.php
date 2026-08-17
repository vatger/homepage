<?php

namespace App\Http\Controllers\Api;

use App\Decorators\ApiPathfinder;
use App\Libraries\VATSIM\ATCBookingsApi;
use App\Models\AtcBooking;
use App\Models\Membership\User;
use App\Models\Navigation\Station;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Event bookings
 *
 * Endpoints to block (book) stations as a vatger event booking. They are
 * used by external event tooling - namely the vatger event manager - to
 * reserve the stations of an event for the assigned controllers.
 *
 * Bookings created here always are vatger event bookings, which means they
 * take precedence over regular bookings: conflicting regular bookings of the
 * same station are removed (and the affected controller is notified).
 */
class EventBookingController extends ApiController
{
    /**
     * The longest time span a single event booking may cover.
     */
    private const MAX_BOOKING_HOURS = 24;

    /**
     * The maximum amount of bookings that may be sent in one request.
     */
    private const MAX_BOOKINGS_PER_REQUEST = 100;

    /**
     * List event bookings
     *
     * Retrieve the vatger event bookings, optionally limited to a single
     * reference and/or a time frame. Without any filter all bookings that
     * have not ended yet are returned.
     */
    #[ApiPathfinder('booking.event.index')]
    public function index(Request $request): JsonResponse
    {
        $this->authorizeApiRequest('booking.event.index');

        $validated = $request->validate([
            'reference' => ['nullable', 'string', 'max:191'],
            'start' => ['nullable', 'date'],
            'end' => ['nullable', 'date'],
        ]);

        $query = AtcBooking::with(['station', 'controller'])->vatgerEvent();

        if (! empty($validated['reference'])) {
            $query->forEventReference($validated['reference']);
        }
        if (! empty($validated['start'])) {
            $query->where('ends_at', '>', Carbon::parse($validated['start'])->utc());
        }
        if (! empty($validated['end'])) {
            $query->where('starts_at', '<', Carbon::parse($validated['end'])->utc());
        }
        if (empty($validated['reference']) && empty($validated['start']) && empty($validated['end'])) {
            $query->future();
        }

        $bookings = $query
            ->orderBy('starts_at')
            ->limit(500)
            ->get()
            ->map(fn (AtcBooking $b) => $this->transform($b));

        return response()->json($bookings->toArray());
    }

    /**
     * Create event bookings
     *
     * Book one or more stations as a vatger event booking. Every entry is
     * handled on its own, a single failing booking does not abort the rest of
     * the request - check the returned result list for the outcome.
     *
     * The optional reference is stored with the booking and allows the caller
     * to look its own bookings up again (and to remove them later on).
     */
    #[ApiPathfinder('booking.event.create')]
    public function store(Request $request): JsonResponse
    {
        $this->authorizeApiRequest('booking.event.create');

        $validated = $request->validate([
            'reference' => ['nullable', 'string', 'max:191'],
            'bookings' => ['required', 'array', 'min:1', 'max:'.self::MAX_BOOKINGS_PER_REQUEST],
            'bookings.*.callsign' => ['required', 'string', 'max:32'],
            'bookings.*.cid' => ['required', 'integer', 'min:1'],
            'bookings.*.start' => ['required', 'date'],
            'bookings.*.end' => ['required', 'date'],
            'bookings.*.reference' => ['nullable', 'string', 'max:191'],
        ]);

        $results = collect($validated['bookings'])
            ->map(fn (array $booking) => $this->book($booking, $validated['reference'] ?? null));

        return response()->json([
            'reference' => $validated['reference'] ?? null,
            'created' => $results->where('status', 'created')->count(),
            'existing' => $results->where('status', 'existing')->count(),
            'conflict' => $results->where('status', 'conflict')->count(),
            'failed' => $results->where('status', 'failed')->count(),
            'results' => $results->values()->toArray(),
        ]);
    }

    /**
     * Delete event bookings
     *
     * Remove vatger event bookings, either all bookings of a reference or a
     * given list of booking ids. Bookings that are not a vatger event booking
     * are never touched.
     */
    #[ApiPathfinder('booking.event.delete')]
    public function destroy(Request $request): JsonResponse
    {
        $this->authorizeApiRequest('booking.event.delete');

        $validated = $request->validate([
            'reference' => ['nullable', 'string', 'max:191'],
            'booking_ids' => ['nullable', 'array', 'max:'.self::MAX_BOOKINGS_PER_REQUEST],
            'booking_ids.*' => ['integer', 'min:1'],
        ]);

        if (empty($validated['reference']) && empty($validated['booking_ids'])) {
            abort(422, 'Either a reference or a list of booking_ids is required.');
        }

        $query = AtcBooking::with(['station', 'controller'])->vatgerEvent();

        if (! empty($validated['reference'])) {
            $query->forEventReference($validated['reference']);
        }
        if (! empty($validated['booking_ids'])) {
            $query->whereIn('id', $validated['booking_ids']);
        }

        $results = $query->get()->map(function (AtcBooking $b) {
            $booking = $this->transform($b);
            $res = ATCBookingsApi::deleteBooking($b);

            return [
                ...$booking,
                'status' => $res['ok'] ? 'deleted' : 'failed',
                'message' => $res['message'],
            ];
        });

        return response()->json([
            'reference' => $validated['reference'] ?? null,
            'deleted' => $results->where('status', 'deleted')->count(),
            'failed' => $results->where('status', 'failed')->count(),
            'results' => $results->values()->toArray(),
        ]);
    }

    /**
     * Book a single station as a vatger event booking
     *
     * @param  array  $input  A single entry of the request payload
     * @param  string|null  $reference  The reference of the whole request
     */
    private function book(array $input, ?string $reference): array
    {
        $callsign = strtoupper(trim($input['callsign']));
        $cid = (int) $input['cid'];
        $reference = $input['reference'] ?? $reference;

        $result = [
            'callsign' => $callsign,
            'cid' => $cid,
            'reference' => $reference,
            'status' => 'failed',
            'message' => '',
            'booking_id' => null,
            'removed_bookings' => 0,
        ];

        $start = Carbon::parse($input['start'])->utc()->startOfMinute();
        $end = Carbon::parse($input['end'])->utc()->startOfMinute();

        if (! $end->isAfter($start)) {
            $result['message'] = 'The end has to be after the start.';

            return $result;
        }
        if ($start->diffInHours($end) > self::MAX_BOOKING_HOURS) {
            $result['message'] = 'A booking can not be longer than '.self::MAX_BOOKING_HOURS.' hours.';

            return $result;
        }
        if ($end->isPast()) {
            $result['message'] = 'The booking has already ended.';

            return $result;
        }

        $station = Station::query()->bookable()->where('ident', $callsign)->first();
        if (! $station) {
            $result['message'] = 'Unknown or not bookable station.';

            return $result;
        }

        $user = User::find($cid);
        if (! $user) {
            $result['message'] = 'Unknown vatsim id.';

            return $result;
        }

        // The very same booking already exists, so there is nothing to do.
        // This keeps the endpoint idempotent for repeated synchronisations.
        $existing = AtcBooking::query()
            ->vatgerEvent()
            ->forStation($station->id)
            ->forAccountId($user->id)
            ->where('starts_at', $start)
            ->where('ends_at', $end)
            ->first();

        if ($existing) {
            if ($reference && $existing->event_reference !== $reference) {
                $existing->event_reference = $reference;
                $existing->save();
            }
            $result['status'] = 'existing';
            $result['message'] = 'Booking already exists.';
            $result['booking_id'] = $existing->id;

            return $result;
        }

        // Another event booking already blocks the station, so we would only
        // run into a rejection of the vatsim booking api.
        $conflict = AtcBooking::query()
            ->vatgerEvent()
            ->forStation($station->id)
            ->where('starts_at', '<', $end)
            ->where('ends_at', '>', $start)
            ->first();

        if ($conflict) {
            $result['status'] = 'conflict';
            $result['message'] = 'The station is already blocked by another event booking.';
            $result['booking_id'] = $conflict->id;

            return $result;
        }

        $b = new AtcBooking;
        $b->station_id = $station->id;
        $b->controller_id = $user->id;
        $b->starts_at = $start;
        $b->ends_at = $end;
        $b->voice = true;
        $b->event = true;
        $b->vatger_event = true;
        $b->training = false;
        $b->exam = false;
        $b->event_reference = $reference;

        // Event bookings take precedence, regular bookings that stand in the
        // way are removed and the controllers of them get notified.
        $result['removed_bookings'] = ATCBookingsApi::deleteBookingsInTheWayForVatgerEvent($b);

        $res = ATCBookingsApi::createAndSaveBooking($b);

        if (! $res['ok']) {
            // Do not keep a local booking that is not known to vatsim, it
            // would block the station without ever showing up anywhere else.
            if ($b->exists) {
                $b->delete();
            }
            $result['message'] = $res['message'];

            return $result;
        }

        $result['status'] = 'created';
        $result['message'] = $res['message'];
        $result['booking_id'] = $b->id;

        return $result;
    }

    /**
     * Bring a booking into the shape the api returns it in
     */
    private function transform(AtcBooking $booking): array
    {
        return [
            'booking_id' => $booking->id,
            'callsign' => $booking->station?->ident,
            'cid' => $booking->controller_id,
            'start' => $booking->starts_at?->toIso8601String(),
            'end' => $booking->ends_at?->toIso8601String(),
            'reference' => $booking->event_reference,
        ];
    }
}
