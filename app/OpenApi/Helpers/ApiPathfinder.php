<?php

namespace App\OpenApi\Helpers;

use Attribute;

#[\Attribute(Attribute::TARGET_METHOD)]
class ApiPathfinder
{
    public function __construct(string $route_id)
    {
    }
}
