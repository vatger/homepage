<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Libraries\VATSIM\EventLibrary;
use Carbon\Carbon;

class EventPagesController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function view(int $id)
    {
        $event = EventLibrary::getEvent($id);
        if (! $event) {
            abort(404);
        }

        return view('pages.event')->with(['event' => $event]);
    }

    public function calendar()
    {
        $events = EventLibrary::getGermanEvents(6);

        $html = '<ul class="block-body">';
        foreach ($events as $event) {
            $start = Carbon::parse($event->start_time);
            $end = Carbon::parse($event->end_time);
            $airports = collect($event->airports)->map(fn ($airport) => $airport->icao)->join(', ');

            $link = route('events.view', ['id' => $event->id]);

            $html .= '<li class="block-row" style="padding-inline: 0;">';
            $html .= '<div class="contentRow">';
            $html .= '<div class="contentRow-figure calendarevents-date-container">';
            $html .= '<div class="calendarevents-date-container-month">'.$start->getTranslatedShortMonthName().'</div>';
            $html .= '<div class="calendarevents-date-container-day">'.$start->dayOfMonth.'</div>';
            $html .= '</div>';
            $html .= '<div class="contentRow-main contentRow-main--close">';
            $html .= '<span class="calendarevents-thread-title">';
            $html .= '<a href="'.$link.'">'.$event->name.'</a>';
            $html .= '</span>';
            $html .= '<div class="contentRow-minor contentRow-minor--hideLinks">';
            $html .= '<span class="calendarevents-forum-title">';
            $html .= $start->format('Hi').'-'.$end->format('Hi').'z';
            $html .= '</span>';
            $html .= '</div>'; // contentRow-minor
            $html .= '<div class="contentRow-minor contentRow-minor--hideLinks">';
            $html .= '<span class="calendarevents-forum-title">';
            $html .= $airports;
            $html .= '</span>';
            $html .= '</div>'; // contentRow-minor
            $html .= '</div>'; // contentRow-main
            $html .= '</div>'; // contentRow
            $html .= '</li>';
        }
        $html .= '</ul>';

        return response($html);

    }
}
