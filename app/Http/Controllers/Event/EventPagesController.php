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
        $allowedOrigin = 'https://board.vatger.de';
        if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] === $allowedOrigin) {
            header('Access-Control-Allow-Origin: '.$allowedOrigin);
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
            header('Access-Control-Allow-Credentials: true');
        }
        // Handle preflight OPTIONS request
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit;
        }

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

        return $html;

    }
}
