<?php

namespace App\Http\Controllers\Api;

use App\Decorators\ApiPathfinder;
use App\Libraries\VATSIM\VATEUDCoreLibrary;
use App\OpenApi\Responses\VateudRosterControllerResponse;
use App\OpenApi\SecuritySchemes\TokenSecurityScheme;
use Illuminate\Http\Request;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class VATEUDCoreContoller extends ApiController
{
    /**
     * Retrieve an array of all CIDs on the roster.
     */
    #[OpenApi\Operation(security: TokenSecurityScheme::class)]
    #[OpenApi\Response(VateudRosterControllerResponse::class)]
    #[ApiPathfinder('vateud.roster.controller')]
    public function roster_controller(Request $request): array
    {
        $this->authorizeApiRequest('vateud.roster.controller');

        return VATEUDCoreLibrary::roster()?->roster_members;
    }
}
