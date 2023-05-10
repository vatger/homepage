<?php

namespace App\OpenApi\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class ErrorModelNotFoundResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::notFound();
    }
}
