<?php

return [
    'atc' => [
        'title' => 'ATC Bookings',
        'text' => [
            'landing' => 'Stations that are booked within the next 5 days.',
            'index' => '',
        ],
        'created' => 'ATC session has been successfully booked.',
        'updated' => 'ATC session details have been successfully updated.',
        'deleted' => 'ATC booking has been deleted!',
        'personal' => [
            'title' => 'Your ATC Bookings',
            'text' => 'All your future ATC session bookings.',
        ],
        'all' => [
            'title' => 'Upcoming Bookings',
            'text' => 'ATC Stations that are booked within VATGER',
        ],
        'errors' => [
            'alreadyBooked' => 'This station is already booked during the selected time frame.',
            'toFarFuture' => 'The selected time slot is too far in the future.',
            'notController' => 'You are not the controller for that session.',
            'notEligable' => 'You must at least hold the S1 rating to be eligable to book any session.',
            'timeframeLimits' =>
                'The timeframe limits have not been met. A session must be at least 60 minutes in duration while not exceeding 24 hours.',
            'timeframePast' => 'Please ensure that the begin is not more than 2 hours in the past.',
            'timeframeSense' => 'Please make sure that the begin is prior to the end.',
            'deleteFailed' => 'Error deleting requested booking. Please try again.',
        ],
        'search' => [
            'from-text' => 'Search bookings starting at this day.',
            'till-text' => 'Search bookings ending on this day.',
            'filter-button-text' => 'Search!',
        ],
        'edit' => [
            'breadcrumb' => 'Edit',
            'title' => 'Edit Booking',
            'date-text' => 'Date of session',
            'start-time-text' => 'Start Time (UTC)',
            'end-time-text' => 'End time (UTC)',
            'station-text' => 'ATS station',
            'voice-text' => 'Session is planned with voice communication available',
            'event-text' => 'This session is part of an event',
            'training-text' => 'This session is a training',
            'save-button-text' => 'Save changes',
        ],
        'create' => [
            'breadcrumb' => 'Create',
            'title' => 'Create Booking',
            'create-button-text' => 'Book new ATC session',
            'date-text' => 'The day the session shall take place.',
            'start-time-text' => 'The time in UTC the session shall start.',
            'end-time-text' => 'The time in UTC the session is planned to end.',
            'station-text' => 'The ATS station the session will take place at.',
            'voice-text' => 'Session is planned with voice communication available.',
            'event-text' => 'This session is part of an event.',
            'training-text' => 'This session is a training.',
            'save-button-text' => 'Book session.',
        ],
    ],
];
