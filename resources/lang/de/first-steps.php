<?php

/**
 * All translations required for the following pages:
 * views/general/firststeps/...
 */

return [
    'introduction' => [
        'title' => 'Willkommen bei VATSIM',
        'breadcrumb' => 'Los Geht Es',

        'text-title' => 'Introduction to vatger',
        'text-content' => [
            '<p class="pb-20">
                vatger ist Mitglied in der <b><a href="https://www.vateud.net" target="_blank">VATSIM Europe Division</a></b>, die wiederum mit weiteren Divisions die <b><a href="https://www.vatsim.eu/" target="_blank">VATSIM Europe, Middle East and Africa Region</a></b> bildet. Insgesamt sind sie Teil des globalen <a href="https://www.vatsim.net" target="_blank"><b>VATSIM</b></a>-Netzwerks, das Piloten, die mit ihrem Flugsimulator an dieses Netzwerk angeschlossen sind, ATC-Dienste über das Internet kostenlos zur Verfügung stellt.
            </p><p>
                Viel Spaß im virtuellen deutschen Luftraum
            </p>',
        ],

        'newbieday' => [
            'text-content' => [
                'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.',
            ],
            'timer-text' => 'The next Newbieday will take place on:',
            'sign-up-button-content' => 'Sign Up',
        ],

        'role-select' => [
            'pilot-tab' => 'Pilot',
            'atco-tab' => 'Air Traffic Controller',

            'pilot' => [
                'how-to-become' => 'How to become a virtual pilot?',
                'how-to-become-content' => [
                    'Our world of flight simulation enthusiasts consists of many groups: from the beginner who has just decided to dedicate himself to this hobby to the well-trained expert who uses the VATSIM network to indulge in his hobby almost like in real flight operations.
                    <br><br>
                    In between, there are many shades and forms. One of them is the pilot who controls his simulator well but essentially flies with simulator generated traffic as well as ATC radio. His goal is actually to fly in a simulation network but sees a big hurdle and lack of knowledge for himself. Also, the new environment - ATC, radio, traffic, airfield, airspace structure - may be frightening and he may be worried about doing something wrong and embarrassing himself.',
                ],

                'button-content' => 'First Steps as a pilot',
            ],

            'atco' => [
                'how-to-become' => 'How to become a virtual air traffic controller?',
                'how-to-become-content' => [
                    'Even for a virtual air traffic controller, a lot of knowledge and skills are required. The following pages should give you an overview of what you are getting into and at the same time show you what the first steps are to start your training.
                    <br><br>
                    To be clear, this is not something you can do on the side. You have to invest a bit of time and will to learn in order to offer fun and professionalism for yourself and others.',
                ],
                'previous-knowledge' => 'Is previous knowledge necessary?',
                'previous-knowledge-content' => [
                    'Apart from a reasonable command of the English language: no.
                    <br><br>
                    However, good spatial awareness, ability to concentrate and patience are an advantage.',
                ],

                'button-content' => 'First Steps as an Air Traffic Controller',
            ],
        ],

        'vatger-services' => [
            'text-title' => 'vatger Services',
            'text-content' => ['Lorem Ipsum ...'],
        ],
    ],

    'become-atco' => [
        'title' => 'Getting started as an Air Traffic Controller',
        'breadcrumb' => 'First Steps',

        'content' => [
            [
                'title' => 'VATSIM Registration',
                'content' => [
                    "<p>To use the VATSIM network, you must have a VATSIM account. If you already have a VATSIM account, you can skip this step. </p>
                     <p>Otherwise, create a new account at <a href='https://my.vatsim.net/register' class='link'>https://my.vatsim.net/register</a>. Enter your data as described and select:</p>
                     
                     <ol>
                        <li>Region: Europe, Middle East and Africa</li>
                        <li>Division: Europe (Except UK)</li>
                     </ol>
                     
                     <p>Please do <b>not</b> use fictitious names or similar.</p>
                     
                     <p class='border-bottom'></p>
                     
                     <p>After successful registration you should receive a so-called VATSIM-ID (CID) and a password by E-mail. You can use this to connect to the network with the pilot client or to log in to services that use <a class='link' href='https://auth.vatsim.net/'>VATSIM Connect</a>.</p>
                     
                     <ul>
                        <li>If you forget your password you can reset it <a class='link' href='https://my.vatsim.net/reset'>here</a>.</li>
                        <li>If you have forgotten your Vatsim ID or email or would like to change your details, contact <a class='link' href='https://membership.vatsim.net/open.php'>Membership Support of VATSIM</a>.</li>
                        <li>If you have not used your account for a long time, it may be blocked, you have to reactivate it <a class='link' href='https://my.vatsim.net/reactivate'>here</a>.</li>
                     </ul>
                     
                     <div class='alert alert-danger'>Caution: If you already have a VATSIM Account, do <b>not</b> create a second VATSIM account!</div>
                     
                     <p>If you wish to change your region and / or division, you can do so <a class='link' href='https://my.vatsim.net/user/region''>here</a>.</p>
                     ",
                ],
            ],
            [
                'title' => 'VATSIM New Member Orientation Test',
                'content' => [
                    "<p>All members who have registered after 18.08.2020 must pass the New Member Orientation Test before they can fly on VATSIM. The required content can be found <a class='link' href='https://my.vatsim.net/learn'>here</a>.</p>

                    <p>After the time in the upper right corner of the Pilot Learning Center has expired, the test can be started <a class='link' href='https://my.vatsim.net/exams'>here</a>.</p>
                    
                    <ul>
                        <li>The test is a multiple choice test consisting of 15 questions.</li>
                        <li>To pass, you must answer at least 80% of the questions (i.e. 12) correctly.</li>
                        <li>The test is limited to 15 minutes.</li>
                        <li>If you do not pass, see what you did wrong. You can start the test again in one hour.</li>
                        <li>Tip: You can look back at the material during the test and refer to it.</li>
                    </ul>
                    
                    <p class='border-bottom'></p>
                    
                    <p>Upon successful completion, you will receive your P0 pilot rating and can connect to the network as a pilot!</p>
                    ",
                ],
            ],
            [
                'title' => 'Assignment EMEA/EUD/GER',
                'content' => ['todo'],
            ],
            [
                'title' => 'Registration vatger',
                'content' => [
                    "<h6>Registration Procedure</h6>
                    <p>...</p>
                    
                    <p class='border-bottom'></p>
                    
                    <h6>Registration Procedure</h6>
                    <p>...</p>
                    
                    <p class='border-bottom'></p>
                    
                    <h6>Teamspeak</h6>
                    <p>...</p>
                    
                    <p class='border-bottom'></p>
                    
                    <h6>Wiki</h6>
                    <p>...</p>
                    
                    
                    ",
                ],
            ],
            [
                'title' => 'Join a regional group (optional)',
            ],
        ],
    ],

    'become-pilot' => [
        'title' => 'Getting started as an Virtual Pilot',
        'breadcrumb' => 'First Steps',

        'content' => [
            [
                'title' => 'VATSIM Registration',
                'content' => [
                    "<p>To use the VATSIM network, you must have a VATSIM account. If you already have a VATSIM account, you can skip this step. </p>
                     <p>Otherwise, create a new account at <a href='https://my.vatsim.net/register' class='link'>https://my.vatsim.net/register</a>. Enter your data as described and select:</p>
                     
                     <ol>
                        <li>Region: Europe, Middle East and Africa</li>
                        <li>Division: Europe (Except UK)</li>
                     </ol>
                     
                     <p>Please do <b>not</b> use fictitious names or similar.</p>
                     
                     <p class='border-bottom'></p>
                     
                     <p>After successful registration you should receive a so-called VATSIM-ID (CID) and a password by E-mail. You can use this to connect to the network with the pilot client or to log in to services that use <a class='link' href='https://auth.vatsim.net/'>VATSIM Connect</a>.</p>
                     
                     <ul>
                        <li>If you forget your password you can reset it <a class='link' href='https://my.vatsim.net/reset'>here</a>.</li>
                        <li>If you have forgotten your Vatsim ID or email or would like to change your details, contact <a class='link' href='https://membership.vatsim.net/open.php'>Membership Support of VATSIM</a>.</li>
                        <li>If you have not used your account for a long time, it may be blocked, you have to reactivate it <a class='link' href='https://my.vatsim.net/reactivate'>here</a>.</li>
                     </ul>
                     
                     <div class='alert alert-danger'>Caution: If you already have a VATSIM Account, do <b>not</b> create a second VATSIM account!</div>
                     
                     <p>If you wish to change your region and / or division, you can do so <a class='link' href='https://my.vatsim.net/user/region''>here</a>.</p>
                     ",
                ],
            ],
            [
                'title' => 'VATSIM New Member Orientation Test',
                'content' => [
                    "<p>All members who have registered after 18.08.2020 must pass the New Member Orientation Test before they can fly on VATSIM. The required content can be found <a class='link' href='https://my.vatsim.net/learn'>here</a>.</p>

                    <p>After the time in the upper right corner of the Pilot Learning Center has expired, the test can be started <a class='link' href='https://my.vatsim.net/exams'>here</a>.</p>
                    
                    <ul>
                        <li>The test is a multiple choice test consisting of 15 questions.</li>
                        <li>To pass, you must answer at least 80% of the questions (i.e. 12) correctly.</li>
                        <li>The test is limited to 15 minutes.</li>
                        <li>If you do not pass, see what you did wrong. You can start the test again in one hour.</li>
                        <li>Tip: You can look back at the material during the test and refer to it.</li>
                    </ul>
                    
                    <p class='border-bottom'></p>
                    
                    <p>Upon successful completion, you will receive your P0 pilot rating and can connect to the network as a pilot!</p>
                    ",
                ],
            ],
            [
                'title' => 'Assignment EMEA/EUD/GER',
                'content' => ['todo'],
            ],
            [
                'title' => 'Registration vatger',
                'content' => [
                    "<h6>Registration Procedure</h6>
                    <p>...</p>
                    
                    <p class='border-bottom'></p>
                    
                    <h6>Registration Procedure</h6>
                    <p>...</p>
                    
                    <p class='border-bottom'></p>
                    
                    <h6>Teamspeak</h6>
                    <p>...</p>
                    
                    <p class='border-bottom'></p>
                    
                    <h6>Wiki</h6>
                    <p>...</p>
                    
                    
                    ",
                ],
            ],
            [
                'title' => 'Join a regional group (optional)',
            ],
        ],
    ],
];
