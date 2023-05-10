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

    /**
     * Test title
     *
     * test text nedjgfhnjfdhbgijfdbngjikvbdfusiosnbvjikdfgs bvjionfdgijbvijbgfd
     */
    #[OpenApi\Operation(security: TokenSecurityScheme::class)]
    public function test()
    {
        //dd(Auth::guard('api')->check());
        return [
            'token' => $this->token,
            'token_user' => $this->token_user,
        ];
    }

    public function bookstack()
    {
        //return BookstackLibrary::_users_read(1234027);
        //return BookstackLibrary::_users_update(1234027, [1, 4, 8, 9]);
    }
}
