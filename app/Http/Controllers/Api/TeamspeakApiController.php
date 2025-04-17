<?php

namespace App\Http\Controllers\Api;

use App\Decorators\ApiPathfinder;
use App\Models\TeamspeakRegistration;

class TeamspeakApiController extends ApiController
{
    /**
     * Teamspeak DBIDs
     *
     * Generate a list of Teamspeak DBIDs for a given CID.
     *
     * @param  int  $cid  the users VATSIM ID
     */
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
