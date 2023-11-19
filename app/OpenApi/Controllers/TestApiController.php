<?php

namespace App\OpenApi\Controllers;

use App\OpenApi\Controllers\ApiController;
use App\OpenApi\SecuritySchemes\TokenSecurityScheme;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

class TestApiController extends ApiController
{
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
