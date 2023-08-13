<?php

namespace App\Http\Controllers\Administration\Event;

use App\Http\Controllers\Controller;
use App\Libraries\VatBook\VatBookLibrary;
use App\Libraries\VATSIM\EventLibrary;
use App\Models\AtcBooking;
use App\Models\Booking\CollectiveBooking;
use App\Models\Navigation\Aerodrome;
use App\Models\Navigation\Station;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Stevebauman\Purify\Facades\Purify;

class BookingController extends Controller
{
    protected VatBookLibrary $_vatBook;

    function __construct()
    {
        parent::__construct();

        $this->_vatBook = new VatBookLibrary();
    }

    /**
     * Display a list of upcoming events
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $events = Collection::make(json_decode(EventLibrary::getEvents(1000, true)));
        $collectivebookings = CollectiveBooking::all();
        $stations = Station::query()->paginate(15);
        return $this->prepareView('administration.event.booking.index')->with([
            'events' => $events,
            'collectivebookings' => $collectivebookings,
            'stations' => $stations,
        ]);
    }

    /**
     * Display the "booking" form for a given event
     *
     * @param Request $request
     * @param int $eventId
     * @return View
     */
    public function show(Request $request, $eventId): View
    {
        $event = $this->_loadEvent($eventId);
        $icaos = [];
        foreach ($event->airports as $a) {
            $icaos[] = $a->icao;
        }
        $aerodromes = Aerodrome::whereIn('icao', $icaos)
            ->with('stations')
            ->get();

        return $this->prepareView('administration.event.booking.show')
            ->with('event', $event)
            ->with('aerodromes', $aerodromes);
    }

    /**
     * Save the bookings for the given event
     *
     * @param Request $request
     * @param int $eventId
     * @return Redirect
     */
    public function update(Request $request, $eventId): RedirectResponse
    {
        $event = $this->_loadEvent($eventId);
        $icaos = [];
        foreach ($event->airports as $a) {
            $icaos[] = $a->icao;
        }
        $aerodromes = Aerodrome::query()
            ->whereIn('icao', $icaos)
            ->with('stations')
            ->get();

        // Set start and end time. Those are given by the event
        $startsAt = Carbon::parse($event->start_time);
        $endsAt = Carbon::parse($event->end_time);

        $errors = [];
        $booked = '';

        // Go over all station{StationId} form fields and create a booking for them
        foreach ($aerodromes as $a) {
            foreach ($a->stations as $s) {
                if ($request->has('station' . $s->id) && $request->get('station' . $s->id) && $s->bookable) {
                    // Avoid double bookings.
                    //if(AtcBooking::whereBetween('starts_at', [$startsAt, $endsAt])->where('station_id', $s->id)->orWhereBetween('ends_at', [$startsAt, $endsAt])->where('station_id', $s->id)
                    //    ->exists())
                    //    continue;
                    // done later

                    // Create the local database entry
                    $booking = AtcBooking::create([
                        'starts_at' => $startsAt,
                        'ends_at' => $endsAt,
                        'controller_id' => $this->_user->id,
                        'station_id' => $s->id,
                        'voice' => true,
                        'training' => false,
                        'event' => true,
                        'event_id' => $event->id,
                    ]);
                    // Send the booking to VATBOOK
                    $this->_vatBook->insert($booking);

                    // FAILS WITH 201
                    // $message = ATCBookingsApi::createAndSaveBooking($booking);
                    // $booking->loadMissing('station');

                    // if($message !== true) {
                    //     $errors[] = $booking->station->ident . ": " . $message;
                    //     $booking->delete();
                    // }
                    // else
                    // {
                    //     $booked = $booked . " " . $booking->station->ident;
                    // }
                }
            }
        }

        if (empty($errors)) {
            return redirect()
                ->route('administration.event.booking.show', ['eventId' => $eventId])
                ->withSuccess('Stations booked: ' . $booked);
        } else {
            return redirect()
                ->route('administration.event.booking.show', ['eventId' => $eventId])
                ->withErrors($errors);
        }
    }

    private function _loadEvent($eventId)
    {
        $event = EventLibrary::getEvent($eventId);
        if ($event === false) {
            abort(404, 'Event with id ' . $eventId . ' could not be found!');
        }
        $event->description = Purify::clean($event->description);
        return $event;
    }
}
