<?php

namespace App\OpenApi\Controllers;

use App\Libraries\MoodleLibrary;
use App\OpenApi\Helpers\ApiPathfinder;
use App\OpenApi\SecuritySchemes\TokenSecurityScheme;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class MoodleController extends ApiController
{
    /**
     * Moodle user info endpoint
     *
     * @param  int  $cid  the users VATSIM ID
     */
    #[OpenApi\Operation(security: TokenSecurityScheme::class)]
    #[ApiPathfinder('moodle.api')]
    public function find_user(int $cid): ?object
    {
        $this->authorizeApiRequest('moodle.api');

        return MoodleLibrary::findUser($cid);
    }

    /**
     * Moodle quiz results endpoint
     *
     * @param  int  $cid  the users VATSIM ID
     * @param  int  $cmid  the course module id of the quiz (see the URL)
     */
    #[OpenApi\Operation(security: TokenSecurityScheme::class)]
    #[ApiPathfinder('moodle.api')]
    public function find_quiz_results(int $cid, int $cmid): ?array
    {
        $this->authorizeApiRequest('moodle.api');

        return MoodleLibrary::findQuizResults($cmid, $cid);
    }

    /**
     * Moodle course completion endpoint
     *
     * @param  int  $cid  the users VATSIM ID
     * @param  int  $course_id  the id of the course
     */
    #[OpenApi\Operation(security: TokenSecurityScheme::class)]
    #[ApiPathfinder('moodle.api')]
    public function find_course_completion(int $cid, int $course_id): ?object
    {
        $this->authorizeApiRequest('moodle.api');

        return MoodleLibrary::findCourseCompletion($course_id, $cid);
    }
}
