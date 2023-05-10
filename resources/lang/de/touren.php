<?php

return [
    'rules' => [
        'rule' => 'Regeln',
        'begin' => 'Vor Beginn der Tour muss eine Anmeldung zur Tour
                    erfolgen',
        'online' => 'Alle Touren und Legs müssen online geflogen werden',
        'fr' => 'Touren können entweder IFR/VFR only oder IFR oder VFR geflogen
                    werden',
        'atyp' => 'Der Flugzeugtyp darf frei gewählt werden, außer anders von
                    der Tour festgelegt',
        'callsign' => 'Das Callsign darf frei gewählt und geändert werden',
        'pause' => 'die Tour darf pausiert und unterbrochen werden',
        'fpl' => 'Ein Flugplan muss immer aufgegeben werden',
        'order' => 'Wenn vorgegeben müssen die Legs in der vorgeschriebenen
                    Reihenfolge geflogen werden',
    ],
    'nachreichen' => [
        'text' => 'Falls das Leg nicht nach 2 Stunden automatisch erkannt wurde, müsst ihr die manuelle Validierung
                    anfragen. Dafür geht ihr einfach auf das Leg, das nicht validiert ist,
                    Und drückt dann auf manuelle Validierung. Füllt nun das geöffnete Dialog aus. Nun müsst ihr nur noch
                    auf Nachtrag anfragen drücken und erledigt. Das Leg wird dann von
                    PR und Events manuell nachvalidiert.',
        'bsp' => 'Beispiel',
    ],
];
