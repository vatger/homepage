<?php

namespace App\OpenApi\Parameters;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\ParametersFactory;

class SendNotificationsParameters extends ParametersFactory
{
    /**
     * @return Parameter[]
     */
    public function build(): array
    {
        return [
            Parameter::query()
                ->name('title')
                ->required()
                ->schema(Schema::string()),
            Parameter::query()
                ->name('message')
                ->required()
                ->description('markdown supported')
                ->schema(Schema::string()),
            Parameter::query()
                ->name('source_name')
                ->required()
                ->schema(Schema::string()),
            Parameter::query()
                ->name('link_text')
                ->allowEmptyValue()
                ->schema(Schema::string()),
            Parameter::query()
                ->name('link_url')
                ->allowEmptyValue()
                ->schema(Schema::string()),
        ];
    }
}
