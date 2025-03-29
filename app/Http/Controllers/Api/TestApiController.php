<?php

namespace App\Http\Controllers\Api;

use App\OpenApi\SecuritySchemes\TokenSecurityScheme;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

class TestApiController extends ApiController
{
    /**
     * Test API Connection
     *
     * Displays information about the connection, like used token etc.
     */
    public function test()
    {
        // $this->authorizeApiRequest('nudel');
        return [
            'token' => $this->token,
            'token_user' => $this->token_user,
        ];
    }
}
