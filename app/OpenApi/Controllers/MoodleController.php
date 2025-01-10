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
     * Moodle quiz attempts endpoint (only finished attempts)
     *
     * @param  int  $cmid  the course module id of the quiz (see the URL)
     * @param  int  $cid  the users VATSIM ID
     */
    #[OpenApi\Operation(security: TokenSecurityScheme::class)]
    #[ApiPathfinder('moodle.api')]
    public function find_quiz_attempts(int $cmid, int $cid): ?array
    {
        $this->authorizeApiRequest('moodle.api');

        return MoodleLibrary::findQuizAttempts($cmid, $cid);
    }

    /**
     * Moodle course completion endpoint
     *
     * @param  int  $cmid  the course module id of the quiz (see the URL)
     * @param  int  $cid  the users VATSIM ID
     * @param  int  $attempts  number of allowed attempts
     */
    #[OpenApi\Operation(security: TokenSecurityScheme::class)]
    #[ApiPathfinder('moodle.api')]
    public function set_overrides(int $cmid, int $cid, int $attempts): bool
    {
        $this->authorizeApiRequest('moodle.api');

        return MoodleLibrary::setQuizOverrides($cmid, $cid, $attempts);
    }

    /**
     * Moodle course completion endpoint
     *
     * @param  int  $course_id  the id of the course
     * @param  int  $cid  the users VATSIM ID
     */
    #[OpenApi\Operation(security: TokenSecurityScheme::class)]
    #[ApiPathfinder('moodle.api')]
    public function find_course_completion(int $course_id, int $cid): ?object
    {
        $this->authorizeApiRequest('moodle.api');

        return MoodleLibrary::findCourseCompletion($course_id, $cid);
    }

    /**
     * Moodle course completion endpoint
     *
     * @param  int  $course_id  the id of the course
     * @param  int  $cid  the users VATSIM ID
     */
    #[OpenApi\Operation(security: TokenSecurityScheme::class)]
    #[ApiPathfinder('moodle.api')]
    public function enrol_user(int $course_id, int $cid): bool
    {
        $this->authorizeApiRequest('moodle.api');

        return MoodleLibrary::enrolUser($course_id, $cid);
    }

    /**
     * Moodle activity completion endpoint
     *
     * @param  int  $cmid  the course module id of the quiz/activity etc. (see the URL)
     * @param  int  $cid  the users VATSIM ID
     */
    #[OpenApi\Operation(security: TokenSecurityScheme::class)]
    #[ApiPathfinder('moodle.api')]
    public function find_activity_completion(int $cmid, int $cid): ?object
    {
        $this->authorizeApiRequest('moodle.api');

        return MoodleLibrary::findActivityCompletion($cmid, $cid);
    }
}
