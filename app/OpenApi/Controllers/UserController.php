<?php

namespace App\OpenApi\Controllers;

use App\Models\Membership\User\User;
use App\Models\Regionalgroup_remove\Regionalgroup;
use App\OpenApi\Parameters\ListUserIdPathParameters;
use App\OpenApi\Responses\ErrorModelNotFoundResponse;
use App\OpenApi\Responses\ErrorUnauthenticatedResponse;
use App\OpenApi\Responses\ListUsersResponse;
use App\OpenApi\SecuritySchemes\TokenSecurityScheme;
use Illuminate\Http\Request;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class UserController extends ApiController
{
    /**
     * Show user
     */
    #[OpenApi\Operation(security: TokenSecurityScheme::class)]
    #[OpenApi\Parameters(factory: ListUserIdPathParameters::class)]
    #[OpenApi\Response(factory: ListUsersResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: ErrorUnauthenticatedResponse::class)]
    #[OpenApi\Response(factory: ErrorModelNotFoundResponse::class)]
    public function userShow(User $id)
    {
        return collect($id)->union([
            'is_mentor' => !empty($this->userMentor($id)),
            'is_atd' => false, //TODO
        ]);
    }

    /**
     * User RG
     */
    #[OpenApi\Operation]
    #[OpenApi\Parameters(factory: ListUserIdPathParameters::class)]
    #[OpenApi\Response(factory: ErrorModelNotFoundResponse::class)]
    public function userRegionalgroups(User $id)
    {
        return $id->regionalgroups
            ->map(function ($rg) use ($id) {
                $mentor = $rg
                    ->mentors()
                    ->where('user_id', $id->id)
                    ->first();
                return [
                    'id' => $rg->id,
                    'fir' => $rg->fir,
                    'name' => $rg->name,
                    'guest' => boolval($rg->pivot->guest),
                    'mentor' => !empty($mentor),
                    'mentor_chief' => boolval($mentor?->pivot->chief),
                    'mentor_senior' => boolval($mentor?->pivot->senior),
                    'joined_at' => $rg->pivot->created_at,
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Mentor
     */
    #[OpenApi\Operation]
    #[OpenApi\Parameters(factory: ListUserIdPathParameters::class)]
    public function userMentor(User $id)
    {
        return Regionalgroup::all()
            ->map(function ($rg) use ($id) {
                $mentor = $rg
                    ->mentors()
                    ->wherePivot('user_id', $id->id)
                    ->first();
                if (!$mentor) {
                    return [];
                }
                return [
                    'rg_id' => $rg->id,
                    'rg_fir' => $rg->fir,
                    'rg_name' => $rg->name,
                    'chief' => boolval($mentor->pivot->chief),
                    'senior' => boolval($mentor->pivot->senior),
                ];
            })
            ->reject(function ($value, $key) {
                return empty($value);
            })
            ->values()
            ->toArray();
    }

    /**
     * User Notification
     */
    #[OpenApi\Operation]
    public function userNotification(User $id, Request $request)
    {
    }
}
