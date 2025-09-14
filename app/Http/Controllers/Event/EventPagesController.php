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

    private static array $HEADERS = [
        'Access-Control-Allow-Origin' => 'https://board.vatsim-germany.org',
        'Access-Control-Allow-Methods' => 'GET',
        'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        'Access-Control-Allow-Credentials' => 'true',
    ];

    public function calendar()
    {
        $events = EventLibrary::getGermanEvents(6);

        $html = '<ul class="block-body">';
        foreach ($events as $event) {
            $start = Carbon::parse($event->start_time);

            $link = route('events.view', ['id' => $event->id]);

            $html .= '<li class="block-row">';
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
            // $html .= '<span class="calendarevents-forum-title">';
            // $html .= '<a href="'.$link.'">'.($event['forum_title']).'</a>';
            // $html .= '</span>';
            $html .= '</div>'; // contentRow-minor
            $html .= '</div>'; // contentRow-main
            $html .= '</div>'; // contentRow
            $html .= '</li>';
        }
        $html .= '</ul>';

        return response($html, 200, self::$HEADERS);

    }
}
