<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse as HttpJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;

class ApiJsonResponse
{
    protected ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function handle(Request $request, Closure $next): mixed
    {
        // First, set the header so any other middleware knows we're
        // dealing with a should-be JSON response.
        $request->headers->set('Accept', 'application/json');
        // Get the response
        $response = $next($request);
        // If the response is not strictly a ApiJsonResponse, we make it
        if (! $response instanceof HttpJsonResponse) {
            $response = $this->responseFactory->json($response->content(), $response->status(), $response->headers->all());
        }

        return $response;
    }
}
