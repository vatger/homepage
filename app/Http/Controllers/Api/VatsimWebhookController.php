<?php

namespace App\Http\Controllers\Api;

use App\Decorators\ApiPathfinder;
use App\Jobs\VatsimWebhookJob;
use Illuminate\Http\Request;

class VatsimWebhookController extends ApiController
{
    /**
     * Process a VATSIM webhook post request
     */
    #[ApiPathfinder('vatsim-webhook.post')]
    public function post(Request $request): void
    {
        $this->authorizeApiRequest('vatsim-webhook.post');

        try {
            $data = json_decode(json_encode($request->json()->all()), false);
            $job = new VatsimWebhookJob($data);
            dispatch($job);
        } catch (\Throwable $exception) {
            // nothing
        }
    }
}
