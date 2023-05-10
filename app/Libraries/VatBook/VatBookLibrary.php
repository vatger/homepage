<?php

namespace App\Libraries\VatBook;

use App\Models\Booking\AtcBooking;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;

/**
 * Class to interact with VATBook
 *
 * Offers create, update and delete funcionality
 */
class VatBookLibrary
{
    /**
     * The base url of the VATBook api
     *
     * @var string
     */
    protected $_vatBookBaseUrl;

    /**
     * Our local event base url
     *
     * @var string
     */
    protected $_eventBaseUrl;

    /**
     * The HTTP Client use to send requests
     *
     * @var GuzzleHttp\Client
     */
    protected $_client;

    /**
     * Indicates if testing mode is active
     * If active, requests will not be saved at the VATBook end
     *
     * @var bool
     */
    private $_testing = true;

    function __construct()
    {
        // Setup class fields
        $this->_vatBookBaseUrl = config('vatbook.baseurl');
        $this->_eventBaseUrl = config('vatbook.eventurl');
        $this->_testing = config('vatbook.testing');

        $handler = HandlerStack::create();

        // If in testing mode, set the TEST flag for all calls
        // to the VATBook endpoint
        if ($this->_testing) {
            $handler->push(
                Middleware::mapRequest(function (RequestInterface $request) {
                    return $request->withUri(Uri::withQueryValue($request->getUri(), 'TEST', '1'));
                }),
            );
        }

        /**
         * Setup the http client
         */
        $this->_client = new Client([
            'base_uri' => $this->_vatBookBaseUrl,
            'handler' => $handler,
        ]);
    }

    /**
     * Send VatBook Insert Request
     * http://vatbook.euroutepro.com/atc/insert.php?Local_URL=&Local_ID=&b_day=&b_month=&b_year=&Controller=&Position=&sTime=&eTime=&T=&E=&voice=.
     *
     * Local_URL - as in common section
     * Local_ID - as in common section
     * b_day - day of month on which the ATC session will start
     * b_month - month part of the start date
     * b_year - year part of the start date (4 digits)
     * Controller - booking user's full name
     * cid - Controller VID (not required but recommended)
     * Position - callsign on which the service will be provided (up to 11 chars)
     * sTime - start time in UTC, 4 digits, no separator between hours and minutes
     * eTime - end time in UTC, as above. If eTime < sTime, then it means booked session ends on the next day
     * T - 0 for normal session, 1 for training (display applications show these differently)
     * E - 0 for normal session, 1 if it is part of event. For events, additional parameter E_URL is required and it specifies a URL where the user will be redirected to
     * voice - 0 for text, 1 for voice, 2 for unknown
     *
     * @param AtcBooking $booking The local booking object to send
     *
     * @return bool
     */
    public function insert(AtcBooking $booking)
    {
        // Send request to VatBook
        $res = $this->_client->get($this->_vatBookBaseUrl . 'insert.asp', [
            'query' => [
                'Local_URL' => 'noredir',
                'Local_ID' => $booking->id,
                'b_day' => $booking->starts_at->format('d'),
                'b_month' => $booking->starts_at->format('m'),
                'b_year' => $booking->starts_at->format('Y'),
                'Controller' => \Illuminate\Support\Str::ascii($booking->controller->firstname . ' ' . $booking->controller->lastname),
                'cid' => $this->_testing ? '1000001' : $booking->controller->id,
                'Position' => $booking->station->ident,
                'sTime' => $booking->starts_at->format('Hi'),
                'eTime' => $booking->ends_at->format('Hi'),
                'T' => $booking->training ? 1 : 0,
                'E' => $booking->event ? 1 : 0,
                'E_URL' => $this->_eventBaseUrl . $booking->event_id,
                'voice' => $booking->voice ? 1 : 0,
            ],
        ]);
        if (200 == $res->getStatusCode()) {
            $body = (string) $res->getBody(); // Cast the returned stream to a string we can work with
            $body = trim($body);

            // We need to find the EU_ID...
            $partials = explode(PHP_EOL, $body);
            /*
             * when an error occured!!!
             * The returned data ALLWAYS IS AN ARRAY OF LENGTH 4
             */
            if (4 != count($partials)) {
                return false;
            }

            // Response Pattern is:
            // array:4 [
            //   0 => "action=insert"
            //   1 => "Local_ID=7"
            //   2 => "EU_ID=384661917"
            //   3 => "Event_ID=0"
            // ]
            // so take [2] and parse the id
            $euid = explode('=', $partials[2])[1];

            $booking->vatbook_id = $euid;
            $booking->save();

            return true;
        } else {
            $booking->delete(); // Unable to book!!!!
            return false;
        }
    }

    /**
     * Send VatBook Update request to
     * http://vatbook.euroutepro.com/atc/update.php?Local_URL=&Local_ID=&EU_ID=&b_day=&b_month=&b_year=&Controller=&Position=&sTime=&eTime=&T=&E=&voice=.
     *
     * Local_URL - as in common section
     * Local_ID - as in common section
     * EU_ID - numeric ID of a booking previously made
     * b_day - day of month on which the ATC session will start
     * b_month - month part of the start date
     * b_year - year part of the start date (4 digits)
     * Controller - booking user's full name
     * cid - Controller VID (not required but recommended)
     * Position - callsign on which the service will be provided (up to 11 chars)
     * sTime - start time in UTC, 4 digits, no separator between hours and minutes
     * eTime - end time in UTC, as above. If eTime < sTime, then it means booked session ends on the next day
     * T - 0 for normal session, 1 for training (display applications show these differently)
     * E - 0 for normal session, 1 if it is part of event. For events, additional parameter E_URL is required and it specifies a URL where the user will be redirected to
     * voice - 0 for text, 1 for voice, 2 for unknown
     *
     * @param AtcBooking $booking [description]
     *
     * @return bool
     */
    public function update(AtcBooking $booking)
    {
        $res = $this->_client->get($this->_vatBookBaseUrl . 'update.asp', [
            'query' => [
                'Local_URL' => 'noredir',
                'Local_ID' => $booking->id,
                'EU_ID' => $booking->vatbook_id,
                'b_day' => $booking->starts_at->format('d'),
                'b_month' => $booking->starts_at->format('m'),
                'b_year' => $booking->starts_at->format('Y'),
                'Controller' => \Illuminate\Support\Str::ascii($booking->controller->firstname . ' ' . $booking->controller->lastname),
                'cid' => $this->_testing ? '1000001' : $booking->controller->id,
                'Position' => $booking->station->ident,
                'sTime' => $booking->starts_at->format('Hi'),
                'eTime' => $booking->ends_at->format('Hi'),
                'T' => $booking->training ? 1 : 0,
                'E' => $booking->event ? 1 : 0,
                'E_URL' => $this->_eventBaseUrl . $booking->event_id,
                'voice' => $booking->voice ? 1 : 0,
            ],
        ]);
        if (200 == $res->getStatusCode()) {
            $body = (string) $res->getBody(); // Cast the returned stream to a string we can work with
            $body = trim($body);

            // We need to find the EU_ID...
            $partials = explode(PHP_EOL, $body);

            /*
             * when an error occured!!!
             * The returned data ALWAYS IS AN ARRAY OF LENGTH 4
             */
            if (4 != count($partials)) {
                return false;
            }

            // Response Pattern is:
            // array:4 [
            //   0 => "action=insert"
            //   1 => "Local_ID=7"
            //   2 => "EU_ID=384661917"
            //   3 => "Event_ID=0"
            // ]
            // so take [2] and parse the id
            $euid = explode('=', $partials[2])[1];

            $booking->vatbook_id = $euid;
            $booking->save();

            return true;
        } else {
            return false;
        }
    }

    /**
     * Send VatBook Delete Request
     * http://vatbook.euroutepro.com/atc/delete.php?Local_URL=noredir&EU_ID=573682004&Local_ID=112.
     *
     * Local_URL - as in common section
     * Local_ID - as in common section
     * EU_ID - as in common section
     *
     * @param AtcBooking $booking [description]
     *
     * @return bool
     */
    public function delete(AtcBooking $booking)
    {
        $res = $this->_client->get($this->_vatBookBaseUrl . 'delete.asp', [
            'query' => [
                'Local_URL' => 'noredir',
                'Local_ID' => $booking->id,
                'EU_ID' => $booking->vatbook_id,
            ],
        ]);

        // $url = $this->vatbookBaseUrl.'delete.php?Local_URL=noredir&EU_ID='.$booking->vatbook_id.'&Local_ID='.$booking->id;
        // $res = $client->get($url);

        if (200 == $res->getStatusCode()) {
            $body = (string) $res->getBody(); // Cast the returned stream to a string we can work with
            $body = trim($body);

            // We need to find the EU_ID...
            $partials = explode(PHP_EOL, $body);

            /*
             * when an error occured!!!
             * The returned data ALLWAYS IS AN ARRAY OF LENGTH 4
             */
            if (4 != count($partials)) {
                return false;
            }

            // Response Pattern is:
            // array:4 [
            //   0 => "action=insert"
            //   1 => "Local_ID=7"
            //   2 => "EU_ID=384661917"
            //   3 => "Event_ID=0"
            // ]
            // so take [1] and parse the id
            $lclid = explode('=', $partials[1])[1];
            if ($lclid == $booking->id) {
                return true;
            }

            return false;
        } else {
            return false;
        }
    }
}
