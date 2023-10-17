<?php

namespace App\OpenApi\Controllers;

use App\Models\Membership\User\User;
use App\OpenApi\Models\ApiToken;
use App\OpenApi\SecuritySchemes\TokenSecurityScheme;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class ApiController extends Controller
{
    protected ?ApiToken $token = null;
    protected ?User $token_user = null;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::guard('api')->check()) {
                $this->token = Auth::guard('api')->user();
                $this->token_user = $this?->token?->user;
            }
            return $next($request);
        });
    }

    public function authorizeApiRequest(string $endpoint): void
    {
        if (!$this->token) {
            abort(401, 'Unauthenticated or token invalid.');
        }
        //if (!$this->token->can($endpoint)) {
        //    abort(401, 'Token not valid for this endpoint.');
        //}
    }

    public function canApiRequest(string $endpoint): bool
    {
        if (!$this->token) {
            return false;
        }
        //if (!$this->token->can($endpoint)) {
        //    return false;
        //}
        return true;
    }

    /**
     * Test API Connection
     *
     * Displays information about the connection, like used token etc.
     */
    #[OpenApi\Operation(security: TokenSecurityScheme::class)]
    public function test()
    {
        //$this->authorizeApiRequest('nudel');
        return [
            'token' => $this->token,
            'token_user' => $this->token_user,
        ];
    }
}
