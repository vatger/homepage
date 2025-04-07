<?php

namespace App\Http\Controllers\Api;

class TestApiController extends ApiController
{
    /**
     * Test API Connection
     *
     * Displays information about the connection, like used token etc.
     */
    public function test()
    {
        return [
            'token' => $this->token,
            'token_user' => $this->token_user,
        ];
    }
}
