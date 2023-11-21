<?php

namespace App\Libraries;

use App\Models\Membership\User\User;
use App\Models\SurveyKey;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use org\jsonrpcphp\JsonRPCClient;

class LimesurveyLibrary
{
    private string $sessionKey;
    private jsonRPCClient $lsJSONRPCClient;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        try {
            $this->lsJSONRPCClient = new jsonRPCClient(config('survey.url'));
            $this->sessionKey = $this->lsJSONRPCClient->get_session_key(config('survey.uname'), config('survey.pass'));
        } catch (Exception $e) {
            throw new Exception('LimesurveyLibrary cant connect');
        }

        if (is_array($this->sessionKey)) {
            Log::error(json_encode($this->sessionKey));
            throw new Exception('LimesurveyLibrary cant get session');
        }
    }

    public function __destruct()
    {
        $this->lsJSONRPCClient->release_session_key($this->sessionKey);
    }

    public function send_req(string $endpoint, ...$data): object|array|false
    {
        $response = $this->lsJSONRPCClient->$endpoint($this->sessionKey, ...$data);

        if (is_array($response)) {
            $data = json_decode(json_encode($response));
        } else {
            $data = json_decode(json_encode(base64_decode($response)));
        }

        return $data;
    }

    //[
    //  {
    //      +"sid": 833398,
    //      +"surveyls_title": "Wahl Leiter FIR München",
    //      +"startdate": null,
    //      +"expires": null,
    //      +"active": "N",
    //    }
    //]
    public function list_surveys(): array
    {
        return Cache::remember(
            'LimesurveyLibrary.list_surveys',
            10,
            fn() => json_decode(json_encode($this->lsJSONRPCClient->list_surveys($this->sessionKey, null))),
        );
    }

    public function list_survey(int $id): ?object
    {
        $list = $this->list_surveys();
        foreach ($list as $s) {
            if ($s->sid == $id) {
                return $s;
            }
        }
        return null;
    }

    /**
     * @param array<User>|Collection<User> $users
     * @return array<SurveyKey>
     */

    // [
    //    [
    //      "sent" => "N",
    //      "remindersent" => "N",
    //      "remindercount" => 0,
    //      "completed" => "N",
    //      "usesleft" => 1,
    //      "emailstatus" => "OK",
    //      "email" => "hollmann.vatsim@gmail.com",
    //      "lastname" => "Hollmann",
    //      "firstname" => "Paul",
    //      "token" => "LnVRoy8PYSPfknd",
    //      "language" => "",
    //      "tid" => "1",
    //      "participant_id" => null,
    //      "blacklisted" => null,
    //      "validfrom" => null,
    //      "validuntil" => null,
    //      "mpid" => null,
    //    ],
    //  ]
    // oder wenn nicht participants table erstellt
    //  [
    //    "status" => "No survey participants table",
    //  ]

    public function add_participants(int $survey_id, array|Collection $users): array|string
    {
        $users_data = collect($users)
            ->map(
                fn(User $u) => (object) [
                    'email' => $u->email,
                    'lastname' => $u->lastname,
                    'firstname' => $u->firstname,
                ],
            )
            ->toArray();

        $response_data = $this->lsJSONRPCClient->add_participants($this->sessionKey, $survey_id, $users_data, true);

        if (!empty($response_data['status'])) {
            return $response_data['status'];
        }
        $survey = $this->list_survey($survey_id);

        $res = [];
        foreach ($response_data as $index => $response_data_elem) {
            $s = new SurveyKey();
            $s->user_id = $users[$index]->id;
            $s->name = $survey->surveyls_title . '#' . $survey_id;
            $s->token = $response_data_elem['token'];
            $s->url = "https://survey.vatsim-germany.org/index.php?r=survey/index&token=$s->token&sid=$survey_id&lang=de-informal";

            $s->save();
            $res[] = $s;
        }
        return $res;
    }
}

/*
 * $l->send_req('get_survey_properties', 833398)
= {#7009
    +"sid": 833398,
    +"owner_id": 2,
    +"gsid": 1,
    +"admin": "f.soest",
    +"active": "N",
    +"expires": null,
    +"startdate": null,
    +"adminemail": "inherit",
    +"anonymized": "Y",
    +"format": "I",
    +"savetimings": "N",
    +"template": "inherit",
    +"language": "de-informal",
    +"additional_languages": "",
    +"datestamp": "N",
    +"usecookie": "I",
    +"allowregister": "I",
    +"allowsave": "I",
    +"autonumber_start": 0,
    +"autoredirect": "I",
    +"allowprev": "I",
    +"printanswers": "I",
    +"ipaddr": "N",
    +"ipanonymize": "N",
    +"refurl": "N",
    +"datecreated": "2023-11-18 09:46:35",
    +"showsurveypolicynotice": 0,
    +"publicstatistics": "I",
    +"publicgraphs": "I",
    +"listpublic": "N",
    +"htmlemail": "I",
    +"sendconfirmation": "I",
    +"tokenanswerspersistence": "I",
    +"assessments": "I",
    +"usecaptcha": "E",
    +"usetokens": "N",
    +"bounce_email": "inherit",
    +"attributedescriptions": null,
    +"emailresponseto": "inherit",
    +"emailnotificationto": "inherit",
    +"tokenlength": -1,
    +"showxquestions": "I",
    +"showgroupinfo": "I",
    +"shownoanswer": "I",
    +"showqnumcode": "I",
    +"bouncetime": null,
    +"bounceprocessing": "N",
    +"bounceaccounttype": null,
    +"bounceaccounthost": null,
    +"bounceaccountpass": null,
    +"bounceaccountencryption": null,
    +"bounceaccountuser": null,
    +"showwelcome": "I",
    +"showprogress": "I",
    +"questionindex": -1,
    +"navigationdelay": -1,
    +"nokeyboard": "I",
    +"alloweditaftercompletion": "I",
    +"googleanalyticsstyle": "",
    +"googleanalyticsapikey": "",
    +"tokenencryptionoptions": "{ "enabled":"Y","columns":{ "firstname":"N","lastname":"N","email":"N" } }",
  }
 */
