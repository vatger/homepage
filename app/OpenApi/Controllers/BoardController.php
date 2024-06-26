<?php

namespace App\OpenApi\Controllers;

use App\Models\Membership\User\User;
use App\OpenApi\Helpers\ApiPathfinder;
use App\OpenApi\SecuritySchemes\TokenSecurityScheme;
use Illuminate\Http\Request;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class BoardController extends ApiController
{
    /**
     * Create a forum user
     *
     * @param Request $request
     * @return bool
     */
    #[OpenApi\Operation(security: TokenSecurityScheme::class)]
    #[ApiPathfinder('board.create')]
    public function create(Request $request): bool
    {
        $this->authorizeApiRequest('board.create');
        $vatsim_id = $request->get('vatsim_id');
        $forum_id = $request->get('forum_id');

        $u = User::find($vatsim_id);

        if (!$u || $u->settings->forum_id) return false;

        $u->settings->forum_id = $forum_id;

        $u->settings->save();

        return true;
    }
}
