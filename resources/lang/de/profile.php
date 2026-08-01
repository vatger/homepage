<?php

return [
    'profile' => [
        'atc-rating-text' => 'ATC',
        'pilot-rating-text' => 'Pilot',

        'profile' => [
            'personal-details-text' => 'Personal Details',
            'regional-group-text' => 'Your Regionalgroups',
            'registered-on' => 'Registered On',
            'description' => 'Description',
            'language-appearance' => 'Language & Appearance',
            'language' => 'Language',

            'regionalgroup' => [
                'no-regionalgroup' => 'You are not assigned to any Regionalgroup',
                'join-regionalgroup' => 'Join Regionalgroup',
                'full-member' => 'Full Member',
                'guest-member' => 'Guest Member',
                'view-button-content' => 'View Regionalgroup',
                'text-full-member-change' => [
                    'You are already assigned as a full member to the ',
                    '. If you proceed with this change and your request is accepted, you will loose your full member status in this regionalgroup \\
                    and will be assigned as a guest member.',
                ],
            ],
        ],

        'notifications' => [
            'settings-saved-successfully' => 'Settings saved successfully',
            'settings-saved-error' => 'There was an error saving your settings. Please try again later.',
        ],

        'settings' => [
            'save-changes-button-content' => 'Save Changes',
            'custom-email-text' => 'Custom E-Mail',
            'change-local-password-title' => 'Change local password',
            'old-password-text' => 'Old password',
            'new-password-text' => 'New password',
            'retype-new-password-text' => 'Re-type new password',

            'dark-mode-text' => 'Dark mode',
        ],

        'teamspeak' => [
            'teamspeak-access-text' => 'Teamspeak Registration',
            'last-ip-text' => 'Last IP',
            'last-used-text' => 'Last used',
            'remove-button-content' => 'Action',

            'manual-registration' => [
                'button-content' => 'Manual registration',
                'complete-button-content' => 'Complete Manual registration',
                'title' => 'Manual registration',
                'input-placeholder' => 'TS-ID / Identity',
                'information-text' => [
                    'Enter your TS-ID and click on the button labelled "Complete Manual registration". <br><br>

                    You can find your TS-ID under: <span class="text-warning" style="font-family: Consolas">Extras > Identitäten > Eindeutige ID (Experten-Ansicht).</span> <br><br>

                    Please ensure that you are connected to the Teamspeak server, or have been connected previously.',
                ],
            ],
        ],

        'feedback' => [
            'feedback-access-text' => 'Your Controller Feedback',
            'no-feedback' => 'You have not received any feedback.',
        ],

        'menu' => [
            'profile-text' => 'Profile',
            'settings-text' => 'Settings',
            'notification-text' => 'Notification',
            'teamspeak-text' => 'Teamspeak Access',
            'feedback-text' => 'Controller Feedback',
        ],

        'error' => [
            'account-inactive-text' => 'Dein VATSIM Account ist derzeit nicht aktiv. Es ist daher aktuell nicht möglich ein Foren-/Teamspeak Konto bei vatger anzulegen. 
                Besuche die folgende Webseite, um dort dein Konto zu aktivieren:
                <a href="https://my.vatsim.net" target="_blank" class="text-dark" style="text-decoration: underline !important;">https://my.vatsim.net</a>.',
            'contact-support-text' => 'Sollte dies nicht funktionieren, oder treten während des Prozesses weitere Fragen auf, wende dich bitte an den VATSIM Support: <a href="https://support.vatsim.net" target="_blank" class="text-muted" style="text-decoration: underline !important;">https://support.vatsim.net</a>',
        ],

        'languages' => [
            'english' => 'English',
            'german' => 'German',
        ],
    ],
];
