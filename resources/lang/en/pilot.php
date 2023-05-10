<?php

return [
    'aerodromes' => [
        'title' => 'Aerodromes',
        'breadcrumb' => [
            "<li class='breadcrumb-item'>Pilots</li>
                <li class='breadcrumb-item active'>Aerodromes</li>",
        ],

        'view-airport-text' => 'View Airport',

        'search-text' => 'Search for Airports',
        'search-input-placeholder' => 'Enter ICAO, Name, ...',

        'aerodrome' => [
            'error-metar-load-text' => 'Error loading METAR.',
            'breadcrumb' => [
                "<li class='breadcrumb-item'>Pilots</li>
                <li class='breadcrumb-item'><a href=" .
                route('pilots.aerodromes.viewall') .
                '>Aerodromes</a></li>',
            ],

            'upcoming-event-title-text' => 'Next Event',
            'loading-event-text' => 'Loading...',
            'loading-event-failed-content' => ['Failed to load event data. Please try again later.', 'No event data available for this airport.'],

            'online-atc-loading-text' => 'Loading...',
            'online-atc-error-loading' => 'Error loading ATC.',
            'online-atc-zero-stations' => 'No stations currently online.',
            'station-table-header' => ['Station', 'Frequency'],

            'error-stand-load-nostand' => 'No stands available for this airport.',
            'error-stand-load-text' => 'Error loading stand data.',

            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'major' => 'Major',
            'military' => 'Military',
            'minor' => 'Minor',
            'navigation' => [
                'frequency' => 'Frequency',
                'heading' => 'Bearing (mag.)',
                'length' => 'Length',
                'navaid' => 'Ident',
                'navigation' => 'Navigation',
                'remarks' => 'Remarks',
                'runway' => 'Runway',
                'surface' => [
                    'asphalt' => 'Asphalt',
                    'concrete' => 'Concrete',
                    'graded' => 'Graded Earth',
                    'grass' => 'Grass',
                    'sand' => 'Sand',
                    'type' => 'Surface type',
                    'unknown' => 'Unknown',
                    'water' => 'Water',
                ],
                'width' => 'Width',
            ],

            'charts' => [
                'chartfox' => [
                    'warning-title' => 'Caution!',
                    'warning-text' => "You are about to leave VATSIM Germany's webservices to an external resource. Do you wish to proceed?",
                    'button-content' => 'Continue to chartfox.org',
                ],
            ],
        ],
    ],
];
