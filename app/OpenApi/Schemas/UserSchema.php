<?php

namespace App\OpenApi\Schemas;

use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AllOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AnyOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Not;
use GoldSpecDigital\ObjectOrientedOAS\Objects\OneOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\SchemaFactory;

class UserSchema extends SchemaFactory implements Reusable
{
    /**
     * @return AllOf|OneOf|AnyOf|Not|Schema
     */
    public function build(): SchemaContract
    {
        return Schema::object('User')->properties(
            Schema::integer('id')->default(1000001),
            Schema::string('firstname')->default(null),
            Schema::string('lastname')->default(null),
            Schema::string('email')->default(null),
            Schema::boolean('setup_completed')->default(1),
            Schema::string('created_at')
                ->format(Schema::FORMAT_DATE_TIME)
                ->default(null),
            Schema::string('updated_at')
                ->format(Schema::FORMAT_DATE_TIME)
                ->default(null),
        );
    }
}
