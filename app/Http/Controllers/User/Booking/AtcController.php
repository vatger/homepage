<?php

namespace App\Http\Controllers\User\Booking;

use App\Http\Controllers\Controller;
use App\Libraries\VatBook\VatBookLibrary;
use App\Models\AtcBooking;
use App\Models\Navigation\Aerodrome;
use App\Models\Navigation\Station;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function __;
use function back;
use function redirect;

class AtcController extends Controller
{
    protected VatBookLibrary $_vatBook;

    function __construct()
    {
        parent::__construct();

        $this->_vatBook = new VatBookLibrary();
    }

    public function index(Request $request)
    {
        $stations = Station::bookable()->get();
        $aerodromes = Aerodrome::isDe()->get();

        return $this->prepareView('homepage.members.atcbooking.index')->with(['stations' => $stations, 'aerodromes' => $aerodromes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $positions = Station::orderBy('ident', 'ASC')->get();
        return $this->prepareView('booking.atc.create')->with('positions', $positions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date_format:d.m.Y|after_or_equal:today',
            'start_at' => 'required|date_format:H:i',
            'end_at' => 'required|date_format:H:i',
            'position' => 'required|exists:navigation_stations,ident',
        ]);

        $date = Carbon::createFromFormat('d.m.Y', $data['date']);
        $startsAt = Carbon::createFromFormat('H:i', $data['start_at'])->setDateFrom($date);
        $endsAt = Carbon::createFromFormat('H:i', $data['end_at'])->setDateFrom($date);
        if ($startsAt === $endsAt) {
            return back()
                ->withErrors(__('booking.atc.errors.duration'))
                ->withInput();
        }
        if ($startsAt->diffInMinutes($endsAt, false) < 0) {
            $endsAt->addDay();
        }
        if ($startsAt->diffInMinutes(Carbon::now(), false) > 0) {
            return back()
                ->withErrors(__('booking.atc.errors.past'))
                ->withInput();
        }

        $position = Station::bookable()
            ->where('ident', $data['position'])
            ->first();
        if ($position == null) {
            return back()
                ->withErrors(__('booking.atc.errors.station'))
                ->withInput();
        }

        if (
            AtcBooking::whereBetween('starts_at', [$startsAt, $endsAt])
                ->where('station_id', $position->id)
                ->orWhereBetween('ends_at', [$startsAt->copy()->addSecond(), $endsAt])
                ->where('station_id', $position->id)
                ->exists()
        ) {
            return back()
                ->withErrors(__('booking.atc.errors.alreadyBooked'))
                ->withInput();
        }

        $booking = AtcBooking::create([
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'controller_id' => $this->_user->id,
            'station_id' => $position->id,
            'voice' => true,
            'training' => false,
            'event' => false,
        ]);

        if ($request->has('voice')) {
            $booking->voice = $request->get('voice') ? 1 : 0;
        }
        if ($request->has('event')) {
            $booking->event = $request->get('event') ? 1 : 0;
        }
        if ($request->has('training')) {
            $booking->training = $request->get('training') ? 1 : 0;
        }

        if ($this->_vatBook->insert($booking)) {
            return redirect()
                ->route('controllers.booking.index')
                ->withSuccess(__('booking.atc.created'));
        } else {
            return back()->withErrors(__('booking.atc.errors.createFailed'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param AtcBooking $booking
     * @return Response
     */
    public function edit(AtcBooking $booking)
    {
        $positions = Station::orderBy('ident', 'ASC')->get();
        return $this->prepareView('homepage.members.atcbooking.edit')
            ->with('booking', $booking)
            ->with('positions', $positions);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param AtcBooking $booking
     * @return RedirectResponse|Response
     */
    public function update(Request $request, AtcBooking $booking): Response|RedirectResponse
    {
        $data = $request->validate([
            'date' => 'required|date_format:d.m.Y|after_or_equal:today',
            'start_at' => 'required|date_format:H:i',
            'end_at' => 'required|date_format:H:i',
            'position' => 'required|exists:navigation_stations,ident',
        ]);

        $date = Carbon::createFromFormat('d.m.Y', $data['date']);
        $startsAt = Carbon::createFromFormat('H:i', $data['start_at'])->setDateFrom($date);
        $endsAt = Carbon::createFromFormat('H:i', $data['end_at'])->setDateFrom($date);
        if ($startsAt === $endsAt) {
            return back()
                ->withErrors(__('booking.atc.errors.duration'))
                ->withInput();
        }
        if ($startsAt->diffInMinutes($endsAt, false) < 0) {
            $endsAt->addDay();
        }
        if ($startsAt->diffInMinutes(Carbon::now(), false) > 0) {
            return back()
                ->withErrors(__('booking.atc.errors.past'))
                ->withInput();
        }

        $booking->starts_at = $startsAt;
        $booking->ends_at = $endsAt;

        $position = Station::bookable()
            ->where('ident', $data['position'])
            ->first();
        if ($position == null) {
            return back()
                ->withErrors(__('booking.atc.errors.station'))
                ->withInput();
        } else {
            $booking->station_id = $position->id;
        }

        if ($request->has('voice')) {
            $booking->voice = $request->get('voice') ? 1 : 0;
        }
        if ($request->has('event')) {
            $booking->event = $request->get('event') ? 1 : 0;
        }
        if ($request->has('training')) {
            $booking->training = $request->get('training') ? 1 : 0;
        }

        if (
            AtcBooking::where('id', '!=', $booking->id)
                ->whereBetween('starts_at', [$startsAt, $endsAt])
                ->where('station_id', $booking->station_id)
                ->orWhereBetween('ends_at', [$startsAt, $endsAt])
                ->where('station_id', $booking->station_id)
                ->exists()
        ) {
            return back()
                ->withErrors(__('booking.atc.errors.alreadyBooked'))
                ->withInput();
        }

        if ($this->_vatBook->update($booking)) {
            return redirect()
                ->route('controllers.booking.index')
                ->withSuccess(__('booking.atc.updated'));
        } else {
            return back()->withErrors(__('booking.atc.errors.updateFailed'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AtcBooking $booking
     * @return RedirectResponse
     */
    public function destroy(AtcBooking $booking): RedirectResponse
    {
        if ($this->_vatBook->delete($booking)) {
            $booking->delete();
            return redirect()
                ->back()
                ->withSuccess(__('booking.atc.deleted'));
        }

        return redirect()
            ->back()
            ->withErrors(__('booking.atc.errors.deleteFailed'));
    }
}
