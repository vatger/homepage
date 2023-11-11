<?php

namespace App\OpenApi\Controllers;

use App\Models\Membership\TeamspeakRegistration;
use App\OpenApi\Helpers\ApiPathfinder;
use App\OpenApi\SecuritySchemes\TokenSecurityScheme;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

/**
 *
 */
#[OpenApi\PathItem]
class TeamspeakApiController extends ApiController
{
    /**
     * Teamspeak DBIDs
     *
     * Generate a list of Teamspeak DBIDs for a given CID.
     * @param int $cid the users VATSIM ID
     */
    #[OpenApi\Operation(security: TokenSecurityScheme::class)]
    #[ApiPathfinder('teamspeak.ids')]
    public function ids(int $cid): array
    {
        $this->authorizeApiRequest('teamspeak.ids');
        $regs = TeamspeakRegistration::query()
            ->where('user_id', $cid)
            ->select('dbid')
            ->get()
            ->collect();
        return $regs
            ->map(function ($obj) {
                return $obj?->dbid;
            })
            ->toArray();
    }
}
