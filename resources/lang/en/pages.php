<?php

return [
    'common' => [
        'events' => 'Events',
        'members' => 'Members',
        'policies' => 'Policies',
        'no-results' => 'No results',
        'loading' => 'Loading…',
    ],

    'landing' => [
        'hero-fallback' => 'Virtual sky, real passion!',
        'show-more-events' => 'Show more events',
        'no-events' => 'There are currently no upcoming events. Please check back later.',
        'community' => 'Community',
        'partners-title' => 'Our Partners',
        'partners-text' => 'You can find more information',
        'partners-link' => 'here',
    ],

    'aerodromes' => [
        'search-results' => 'The search returned :count results.',
    ],

    'aerodrome' => [
        'elevation' => 'Elevation (ft)',
        'civil' => 'Civil',
        'stand-information' => 'Stand Information',
        'links' => 'Links',
        'active-atc' => 'Active ATC',
        'monitoring' => 'monitoring',
        'no-atc-online' => 'No ATC is currently online.',
    ],

    'event' => [
        'suggested-routes' => 'Suggested Route(s)',
    ],

    'getting-started' => [
        'title' => 'Getting Started',
        'completed' => ':completed of :total completed',
        'steps' => [
            'vatsim-registration' => 'VATSIM Registration',
            'vatger-registration' => 'VATSIM Germany Registration',
            'orientation-test' => 'New Member Orientation Test',
            'assignment' => 'Assignment EMEA / EUD / GER',
        ],
        'not-available' => 'This step is not available yet.',
        'continue' => 'Continue',
        'vatsim' => [
            'title' => 'VATSIM Registration',
            'intro' => 'You need a VATSIM account to use the VATSIM network. If you already have an account, you can skip this step.',
            'create' => 'Otherwise, create an account at <a class="link text-decoration-underline" href="https://my.vatsim.net/register" target="_blank" rel="noopener">my.vatsim.net/register</a>. Use your real details and select:',
            'region' => 'Region: Europe, Middle East and Africa',
            'division' => 'Division: Europe (except UK)',
            'credentials' => 'After registration, you will receive your VATSIM ID (CID) and a password by email. You can use these to connect with a pilot client and sign in to services using VATSIM Connect.',
            'warning' => 'Do not create a second VATSIM account. Multiple accounts will lead to suspension. Contact VATSIM or VATSIM Germany Support if you need help.',
            'password' => 'You can <a class="link text-decoration-underline" href="https://my.vatsim.net/reset" target="_blank" rel="noopener">reset a forgotten password here</a>.',
            'membership' => 'If you forgot your VATSIM ID or email address, or need to change your details, contact <a class="link text-decoration-underline" href="https://membership.vatsim.net/open.php" target="_blank" rel="noopener">VATSIM Membership Support</a>.',
            'reactivate' => 'If your account was suspended due to inactivity, you can <a class="link text-decoration-underline" href="https://my.vatsim.net/reactivate" target="_blank" rel="noopener">reactivate it here</a>.',
        ],
        'vatger' => [
            'title' => 'VATSIM Germany Registration',
            'text' => 'Now sign in to VATSIM Germany using your newly created VATSIM account.',
        ],
    ],

    'membership' => [
        'vatsim-id' => 'VATSIM ID',
        'notifications' => 'Notifications',
        'settings-accounts' => 'Settings/Accounts',
        'teamspeak' => 'Teamspeak',
        'surveys' => 'Surveys',
        'staff' => 'Staff',
        'error' => 'Error',
    ],

    'banned' => [
        'title' => 'Blocked',
        'breadcrumb' => 'Blocked',
        'intro' => 'Your VATSIM Germany account has been blocked. This can have various reasons.',
        'reason' => 'Reason:',
        'inactive' => 'Due to prolonged inactivity, your account was automatically set to “INACTIVE” in the central VATSIM database. VATSIM Germany cannot access this database. You can reactivate your account at <a href="https://my.vatsim.net/reactivate">my.vatsim.net/reactivate</a>. If you encounter problems, please contact the <a href="https://membership.vatsim.net/">VATSIM Membership Department</a>.',
        'orientation-test' => 'It appears that the New Member Orientation Test has not been completed yet.',
        'support' => 'If you have questions, contact <code>support@vatger.de</code>.',
        'refresh' => 'If outdated data is displayed on the VATSIM Germany website, <a href=":url">refresh it here</a>. New data may take up to 12 hours to become available.',
    ],

    'pending-removal' => [
        'title' => 'Data deletion in progress',
        'breadcrumb' => 'Pending Removal',
        'heading' => 'Your VATSIM Germany data is being deleted',
        'text' => 'You requested the deletion of your VATSIM Germany data or did not respond within the period specified in the statutes. We are therefore deleting the data stored by VATSIM Germany. We can only delete data on VATGER servers and cannot access the central VATSIM database. To delete your VATSIM account, contact VATSIM Support at support.vatsim.net. The process may take some time. Afterwards, you can create a new account with us if required.',
        'cancel-title' => 'Cancel deletion',
        'cancel-text' => 'Some data may already have been deleted. You can still cancel the ongoing deletion process here.',
        'cancel-button' => 'Cancel deletion',
    ],

    'policy' => [
        'list-title' => 'Policies',
        'changelog' => 'Change log:',
        'pdf-unavailable' => 'The PDF file cannot be displayed.',
        'download' => 'Download PDF',
        'important' => 'Important Policies',
        'continue' => 'Continue',
        'last-revised' => 'Last revised:',
        'accept' => 'Accept',
        'decline' => 'Decline',
        'accepted-at' => 'Accepted on :date',
        'not-accepted' => 'Not accepted yet',
    ],

    'required-courses' => [
        'title' => 'Required Courses',
        'text' => 'Here you can find stations and their required Moodle courses.',
        'station' => 'Station',
        'courses' => 'Courses',
        'fir' => 'FIR',
    ],

    'restricted' => [
        'title' => 'Restricted Stations',
        'choose-type' => 'Choose restriction type',
    ],

    'stations' => [
        'search-placeholder' => 'Ident, name, frequency',
        'ident' => 'Ident',
        'name' => 'Name',
        'frequency' => 'Frequency',
        's1-tower' => 'S1 Tower',
        's1-theory' => 'S1 Theory Stations',
        'staffing-tool-title' => 'Can I staff this station?',
        'staffing-tool-text' => 'Check your current endorsements with Can I Staff It.',
        'staffing-tool-link' => 'Open Can I Staff It',
    ],
];
