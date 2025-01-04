<?php

namespace App\Libraries;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

class MoodleLibrary extends BaseLibrary
{
    public static function send(string $function, array $data): object|array|null
    {
        $base = config('moodle.base_url');
        $token = config('moodle.token');
        $client = static::constructClient();

        $url = $base.'/webservice/rest/server.php?wstoken='.$token.'&moodlewsrestformat=json&wsfunction='.$function;

        try {
            $res = $client->post($url, [RequestOptions::FORM_PARAMS => $data]);
        } catch (GuzzleException $e) {
            \Log::error($e->getMessage());

            return null;
        }
        $result = json_decode($res->getBody());
        if (is_object($result) && ! empty($result->exception)) {
            return null;
        }

        return $result;
    }

    public static function findUser(int $vatsim_id): ?object
    {
        $results = static::send('core_user_get_users_by_field', [
            'field' => 'username',
            'values' => [$vatsim_id],
        ]);

        return $results ? $results[0] : null;
    }

    public static function findCourseModule(string $course_module_id): ?object
    {
        $results = static::send('core_course_get_course_module', ['cmid' => $course_module_id]);

        return $results?->cm;
    }

    public static function findQuizResults(int $course_module_id, int $vatsim_id, bool $only_finished = true, bool $include_previews = false): ?array
    {
        $user = static::findUser($vatsim_id);
        if (! $user) {
            return null;
        }
        $cm = static::findCourseModule($course_module_id);
        if (! $cm || $cm->modname != 'quiz') {
            return null;
        }

        $results = static::send('mod_quiz_get_user_attempts', [
            'quizid' => $cm->instance,
            'userid' => $user->id,
            'status' => $only_finished ? 'finished' : 'all',
            'includepreviews' => $include_previews ? 1 : 0,
        ]);

        return $results->attempts;
    }

    public static function findCourseCompletion(int $course_id, int $vatsim_id): ?object
    {
        $user = static::findUser($vatsim_id);
        if (! $user) {
            return null;
        }
        $results = static::send('core_completion_get_course_completion_status', [
            'courseid' => $course_id,
            'userid' => $user->id,
        ]);

        return $results->completionstatus;
    }

    public static function findQuizOverrides(int $course_module_id, int $vatsim_id): ?object
    {
        $user = static::findUser($vatsim_id);
        if (! $user) {
            return null;
        }
        $cm = static::findCourseModule($course_module_id);
        if (! $cm || $cm->modname != 'quiz') {
            return null;
        }

        $results = static::send('mod_quiz_get_overrides', [
            'quizid' => $cm->instance,
        ]);
        $overrides = $results->overrides;
        foreach ($overrides as $override) {
            if ($override->userid == $user->id) {
                return $override;
            }
        }

        return null;
    }
}
