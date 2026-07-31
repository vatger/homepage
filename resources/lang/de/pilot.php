<?php

return [
    'aerodromes' => [
        'title' => 'Flugplätze',
        'view-airport-text' => 'Flugplatz anzeigen',
        'search-text' => 'Flugplätze durchsuchen',
        'search-input-placeholder' => 'ICAO, Name, IATA …',
        'aerodrome' => [
            'error-metar-load-text' => 'METAR konnte nicht geladen werden.',
            'upcoming-event-title-text' => 'Nächstes Event',
            'loading-event-text' => 'Wird geladen …',
            'loading-event-failed-content' => [
                'Eventdaten konnten nicht geladen werden. Bitte versuche es später erneut.',
                'Für diesen Flugplatz sind keine Eventdaten verfügbar.',
            ],
            'online-atc-loading-text' => 'Wird geladen …',
            'online-atc-error-loading' => 'ATC-Daten konnten nicht geladen werden.',
            'online-atc-zero-stations' => 'Derzeit sind keine Stationen online.',
            'station-table-header' => ['Station', 'Frequenz'],
            'error-stand-load-nostand' => 'Für diesen Flugplatz sind keine Standplätze verfügbar.',
            'error-stand-load-text' => 'Standplatzdaten konnten nicht geladen werden.',
            'latitude' => 'Breitengrad',
            'longitude' => 'Längengrad',
            'major' => 'Groß',
            'military' => 'Militärisch',
            'minor' => 'Klein',
            'navigation' => [
                'frequency' => 'Frequenz',
                'heading' => 'Richtung (mag.)',
                'length' => 'Länge',
                'navaid' => 'Kennung',
                'navigation' => 'Navigation',
                'remarks' => 'Bemerkungen',
                'runway' => 'Start-/Landebahn',
                'surface' => [
                    'asphalt' => 'Asphalt',
                    'concrete' => 'Beton',
                    'graded' => 'Befestigter Untergrund',
                    'grass' => 'Gras',
                    'sand' => 'Sand',
                    'type' => 'Oberfläche',
                    'unknown' => 'Unbekannt',
                    'water' => 'Wasser',
                ],
                'width' => 'Breite',
            ],
            'charts' => [
                'chartfox' => [
                    'warning-title' => 'Achtung!',
                    'warning-text' => 'Du verlässt die Webdienste von vatger und öffnest eine externe Ressource. Möchtest du fortfahren?',
                    'button-content' => 'Weiter zu chartfox.org',
                ],
            ],
        ],
    ],
];
