<?php

namespace App\OpenApi\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class VateudRosterControllerResponse extends ResponseFactory implements Reusable
{
    public function build(): Response
    {
        $response = Schema::array()->items(
            Schema::object()->properties(
                Schema::integer('user_cid')->example(10000001),
                Schema::string('permitted_upto')->example('DEL/GND'),
            )
        );

        return Response::ok()
            ->statusCode(200)
            ->description('Successful response')
            ->content(MediaType::json()->schema($response));
    }
}
