<?php

return [
    'common' => [
        'events' => 'Events',
        'members' => 'Mitglieder',
        'policies' => 'Richtlinien',
        'no-results' => 'Keine Ergebnisse',
        'loading' => 'Wird geladen …',
    ],

    'landing' => [
        'hero-fallback' => 'Virtueller Himmel, echte Leidenschaft!',
        'show-more-events' => 'Mehr Events anzeigen',
        'no-events' => 'Derzeit gibt es keine bevorstehenden Events. Schau später noch einmal vorbei.',
        'community' => 'Gemeinschaft',
        'partners-title' => 'Unsere Partner',
        'partners-text' => 'Weitere Informationen findest du',
        'partners-link' => 'hier',
    ],

    'aerodromes' => [
        'search-results' => 'Die Suche ergab :count Treffer.',
    ],

    'aerodrome' => [
        'elevation' => 'Höhe (ft)',
        'civil' => 'Zivil',
        'stand-information' => 'Standplatzinformationen',
        'links' => 'Links',
        'active-atc' => 'Aktives ATC',
        'monitoring' => 'überwacht',
        'no-atc-online' => 'Derzeit ist kein ATC online.',
    ],

    'event' => [
        'suggested-routes' => 'Vorgeschlagene Route(n)',
    ],

    'getting-started' => [
        'title' => 'Erste Schritte',
        'completed' => ':completed von :total abgeschlossen',
        'steps' => [
            'vatsim-registration' => 'Registrierung bei VATSIM',
            'vatger-registration' => 'Registrierung bei VATSIM Germany',
            'orientation-test' => 'New Member Orientation Test',
            'assignment' => 'Zuordnung EMEA / EUD / GER',
        ],
        'not-available' => 'Dieser Schritt ist noch nicht verfügbar.',
        'continue' => 'Weiter',
        'vatsim' => [
            'title' => 'Registrierung bei VATSIM',
            'intro' => 'Für die Nutzung des VATSIM-Netzwerks benötigst du einen VATSIM-Account. Wenn du bereits einen Account hast, kannst du diesen Schritt überspringen.',
            'create' => 'Erstelle andernfalls unter <a class="link text-decoration-underline" href="https://my.vatsim.net/register" target="_blank" rel="noopener">my.vatsim.net/register</a> einen Account. Verwende deine echten Daten und wähle:',
            'region' => 'Region: Europe, Middle East and Africa',
            'division' => 'Division: Europe (except UK)',
            'credentials' => 'Nach erfolgreicher Registrierung erhältst du per E-Mail deine VATSIM-ID (CID) und ein Passwort. Damit kannst du dich mit einem Pilotenclient verbinden und bei Diensten mit VATSIM Connect anmelden.',
            'warning' => 'Erstelle keinen zweiten VATSIM-Account. Mehrere Accounts führen zur Sperrung. Wende dich bei Problemen an den VATSIM- oder VATSIM-Germany-Support.',
            'password' => 'Ein vergessenes Passwort kannst du <a class="link text-decoration-underline" href="https://my.vatsim.net/reset" target="_blank" rel="noopener">hier zurücksetzen</a>.',
            'membership' => 'Wenn du deine VATSIM-ID oder E-Mail-Adresse vergessen hast oder Angaben ändern möchtest, kontaktiere den <a class="link text-decoration-underline" href="https://membership.vatsim.net/open.php" target="_blank" rel="noopener">VATSIM Membership Support</a>.',
            'reactivate' => 'Einen wegen längerer Inaktivität gesperrten Account kannst du <a class="link text-decoration-underline" href="https://my.vatsim.net/reactivate" target="_blank" rel="noopener">hier reaktivieren</a>.',
        ],
        'vatger' => [
            'title' => 'Registrierung bei VATSIM Germany',
            'text' => 'Melde dich jetzt mit deinem neu erstellten VATSIM-Account bei VATSIM Germany an.',
        ],
    ],

    'membership' => [
        'vatsim-id' => 'VATSIM-ID',
        'notifications' => 'Benachrichtigungen',
        'settings-accounts' => 'Einstellungen/Konten',
        'teamspeak' => 'Teamspeak',
        'surveys' => 'Umfragen',
        'staff' => 'Staff',
        'error' => 'Fehler',
    ],

    'banned' => [
        'title' => 'Gesperrt',
        'breadcrumb' => 'Sperre',
        'intro' => 'Dein VATSIM-Germany-Account wurde gesperrt. Dies kann verschiedene Gründe haben.',
        'reason' => 'Grund:',
        'inactive' => 'Durch längere Inaktivität wurde dein Account in der zentralen VATSIM-Datenbank automatisch auf „INACTIVE“ gesetzt. VATSIM Germany hat keinen Zugriff auf diese Datenbank. Du kannst deinen Account unter <a href="https://my.vatsim.net/reactivate">my.vatsim.net/reactivate</a> reaktivieren. Bei Problemen kontaktiere bitte das <a href="https://membership.vatsim.net/">VATSIM Membership Department</a>.',
        'orientation-test' => 'Der New Member Orientation Test wurde offenbar noch nicht abgeschlossen.',
        'support' => 'Bei Fragen wende dich an <code>support@vatger.de</code>.',
        'refresh' => 'Werden auf der VATSIM-Germany-Homepage veraltete Daten angezeigt, <a href=":url">aktualisiere sie hier</a>. Es kann bis zu 12 Stunden dauern, bis neue Daten verfügbar sind.',
    ],

    'pending-removal' => [
        'title' => 'Datenlöschung läuft',
        'breadcrumb' => 'Ausstehende Löschung',
        'heading' => 'Deine VATSIM-Germany-Daten werden gelöscht',
        'text' => 'Du hast die Löschung deiner VATSIM-Germany-Daten beantragt oder dich nicht innerhalb der in der Satzung genannten Frist zurückgemeldet. Wir löschen deshalb die bei VATSIM Germany gespeicherten Daten. Wir können nur Daten auf VATGER-Servern löschen und haben keinen Zugriff auf die zentrale VATSIM-Datenbank. Wenn du deinen VATSIM-Account löschen möchtest, kontaktiere den VATSIM Support unter support.vatsim.net. Der Vorgang kann einige Zeit dauern. Anschließend kannst du bei Bedarf erneut einen Account bei uns erstellen.',
        'cancel-title' => 'Löschung abbrechen',
        'cancel-text' => 'Einige Daten wurden möglicherweise bereits gelöscht. Du kannst den laufenden Löschvorgang trotzdem hier abbrechen.',
        'cancel-button' => 'Löschung abbrechen',
    ],

    'policy' => [
        'list-title' => 'Richtlinien',
        'changelog' => 'Änderungsprotokoll:',
        'pdf-unavailable' => 'Die PDF-Datei kann nicht angezeigt werden.',
        'download' => 'PDF herunterladen',
        'important' => 'Wichtige Richtlinien',
        'continue' => 'Weiter',
        'last-revised' => 'Zuletzt geändert:',
        'accept' => 'Akzeptieren',
        'decline' => 'Ablehnen',
        'accepted-at' => 'Akzeptiert am :date',
        'not-accepted' => 'Noch nicht zugestimmt',
    ],

    'required-courses' => [
        'title' => 'Erforderliche Kurse',
        'text' => 'Hier findest du Stationen und die dafür erforderlichen Moodle-Kurse.',
        'station' => 'Station',
        'courses' => 'Kurse',
        'fir' => 'FIR',
    ],

    'restricted' => [
        'title' => 'Beschränkte Stationen',
        'choose-type' => 'Art der Beschränkung auswählen',
    ],

    'stations' => [
        'search-placeholder' => 'Kennung, Name, Frequenz',
        'ident' => 'Kennung',
        'name' => 'Name',
        'frequency' => 'Frequenz',
        's1-tower' => 'S1-Tower',
        's1-theory' => 'S1-Theoriestationen',
        'staffing-tool-title' => 'Darf ich diese Station besetzen?',
        'staffing-tool-text' => 'Prüfe deine aktuellen Berechtigungen mit Can I Staff It.',
        'staffing-tool-link' => 'Can I Staff It öffnen',
    ],
];
