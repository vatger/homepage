<?php

namespace App\Http\Controllers\Api;

use App\Decorators\ApiPathfinder;
use App\Libraries\VATSIM\VATEUDCoreLibrary;
use Illuminate\Http\Request;

class VATEUDCoreContoller extends ApiController
{
    /**
     * Retrieve an array of all CIDs on the roster.
     */
    #[ApiPathfinder('vateud.roster.controller')]
    public function roster_controller(Request $request): array
    {
        $this->authorizeApiRequest('vateud.roster.controller');

        return VATEUDCoreLibrary::roster()?->roster_members;
    }
}
