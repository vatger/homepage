<?php

namespace App\OpenApi\Controllers;

use App\Jobs\VatsimWebhookJob;
use App\OpenApi\Helpers\ApiPathfinder;
use App\OpenApi\SecuritySchemes\TokenSecurityScheme;
use Illuminate\Http\Request;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class VatsimWebhookController extends ApiController
{


    /**
     * Process a VATSIM webhook post request
     *
     * @param Request $request
     */
    #[OpenApi\Operation(security: TokenSecurityScheme::class)]
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
