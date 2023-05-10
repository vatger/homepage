<?php

namespace App\Http\Controllers\User\Booking;

use App\Http\Controllers\Controller;
use App\Models\Booking\AtcBooking;
use App\Models\Navigation\Aerodrome;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use function abort;
use function collect;

class AtcApiController extends Controller
{
    /**
     * Retrieve a collection of AtcBooking
     * between a given start and end date
     *
     * @param string $start The start date in format Y-m-d
     * @param string $end The end date in format Y-m-d
     * @return Collection<AtcBooking>
     */
    public function index(Request $request, $start, $end = null): Collection
    {
        // if(!$request->ajax()) abort(403, 'GET Request not allowed!');

        $s = Carbon::createFromFormat('Y-m-d', $start);
        $s->setTime(0, 0, 0);

        $e = $end != null ? Carbon::createFromFormat('Y-m-d', $end) : $s->copy();
        $e->setTime(23, 59, 59);

        $bookings = collect();
        // Let's grab bookings for GERMAN airports
        if (Auth::check()) {
            // Authenticated user. We can show controller names
            foreach (
                Aerodrome::isDe()
                    ->orderBy('icao', 'ASC')
                    ->orderBy('major')
                    ->with([
                        'stations' => function ($query) use ($s, $e) {
                            return $query->with([
                                'bookings' => function ($query) use ($s, $e) {
                                    return $query
                                        ->whereBetween('starts_at', [$s, $e])
                                        ->orWhereBetween('ends_at', [$s, $e])
                                        ->with('controller');
                                },
                            ]);
                        },
                    ])
                    ->get()
                as $aerodrome
            ) {
                $bookings->push($aerodrome);
            }
        } else {
            // We must hide ( not load at all ) controller names
            foreach (
                Aerodrome::isDe()
                    ->orderBy('icao', 'ASC')
                    ->orderBy('major')
                    ->with([
                        'stations' => function ($query) use ($s, $e) {
                            return $query->with([
                                'bookings' => function ($query) use ($s, $e) {
                                    return $query->whereBetween('starts_at', [$s, $e])->orWhereBetween('ends_at', [$s, $e]);
                                },
                            ]);
                        },
                    ])
                    ->get()
                as $aerodrome
            ) {
                $bookings->push($aerodrome);
            }
        }

        $filtered = $bookings->filter(function ($value, $key) {
            if (count($value->stations) > 0) {
                foreach ($value->stations as $station) {
                    if (count($station->bookings) > 0) {
                        return true;
                    }
                }
            }
            return false;
        });
        return $filtered->flatten();
    }

    /**
     * Get bookings based on the request parameters
     *
     * @param Request
     * @return Collection<AtcBooking>
     */
    public function show(Request $request): Collection
    {
        if (!$request->ajax()) {
            abort(403, 'No Ajax request supplied.');
        }

        try {
            $s = Carbon::createFromFormat('d.m.Y H:i', $request->get('report-start-date'));
            $e = Carbon::createFromFormat('d.m.Y H:i', $request->get('report-end-date'));

            $bookings = AtcBooking::orderBy('starts_at', 'ASC')
                ->with('station', 'controller')
                ->whereBetween('starts_at', [$s, $e])
                ->orWhereBetween('ends_at', [$s, $e])
                ->get();

            return $this->sortBookings($bookings);
        } catch (\Throwable $th) {
            return collect(); // Return empty collection if something failed
        }
    }

    /**
     * Get bookings based on the request parameters
     *
     * @param Request
     * @return Collection<AtcBooking>
     */
    public function personal(Request $request): Collection
    {
        if (!$request->ajax()) {
            abort(403, 'No Ajax request supplied.');
        }

        $bookings = AtcBooking::forAccountId(Auth::user()->id)
            // ->future()
            ->orderBy('starts_at', 'ASC')
            ->with('station', 'controller')
            ->get();

        return $this->sortBookings($bookings);
    }

    /**
     * Sort the given bookings by date
     * and add the divider flag if the
     * booking is at another date than
     * the previous one
     *
     * @return Collection<AtcBooking>
     */
    protected function sortBookings($bookings): Collection
    {
        if (sizeof($bookings) > 1) {
            $bookings = $bookings
                ->sortBy([fn($a, $b) => $a->starts_at <=> $b->starts_at, fn($a, $b) => strnatcmp($a->station->ident, $b->station->ident)])
                ->values()
                ->all();
        }

        for ($i = 0; $i < sizeof($bookings); $i++) {
            if ($i == 0) {
                $bookings[$i]->divider = true;
            } else {
                if ($bookings[$i]->starts_at->format('d.m.Y') != $bookings[$i - 1]->starts_at->format('d.m.Y')) {
                    $bookings[$i]->divider = true;
                } else {
                    $bookings[$i]->divider = false;
                }
            }
        }

        return collect($bookings);
    }
}
