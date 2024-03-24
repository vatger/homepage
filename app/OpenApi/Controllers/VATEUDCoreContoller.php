<?php

namespace App\OpenApi\Controllers;

use App\Libraries\VATSIM\VATEUDCoreLibrary;
use App\Models\AtcBooking;
use App\OpenApi\Helpers\ApiPathfinder;
use App\OpenApi\Responses\VateudRosterControllerResponse;
use App\OpenApi\SecuritySchemes\TokenSecurityScheme;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class VATEUDCoreContoller extends ApiController
{
    /**
     * Retrieve an array of all CIDs on the roster.
     *
     * @param Request $request
     * @return array
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
