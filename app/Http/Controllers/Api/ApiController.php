<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\ApiLog;
use App\Models\Api\ApiToken;
use App\Models\Membership\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use ReflectionClass;

class ApiController extends Controller
{
    protected ?ApiToken $token = null;

    protected ?User $token_user = null;

    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {
            if (Auth::guard('api')->check()) {
                $this->token = Auth::guard('api')->user();
                $this->token_user = $this?->token?->user;
            }

            $log = [
                'token_id' => $this->token?->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'endpoint' => $request->path(),
                'ip_address' => substr($request->ip(), 0, 45),
            ];

            ApiLog::query()->create($log);

            return $next($request);
        });
    }

    public function authorizeApiRequest(string $route_id): void
    {
        if (! $this->token) {
            abort(401, 'Unauthenticated or token invalid.');
        }
        if (! $this->token->check_allowed($route_id)) {
            abort(401, 'Token not valid for this endpoint.');
        }
    }

    public function canApiRequest(string $route_id): bool
    {
        if (! $this->token) {
            return false;
        }
        if (! $this->token->check_allowed($route_id)) {
            return false;
        }

        return true;
    }

    //
    private static function collect_classes(): array
    {
        $files = collect(scandir(__DIR__));
        $classes = $files->map(fn ($file) => str_replace('.php', '', $file))->filter(fn ($file) => strlen($file) > 3);

        return $classes->toArray();
    }

    public static function collect_paths(): array
    {
        $prefix = str_replace('ApiController', '', self::class);

        return \Cache::remember('openapi.collect_paths', 10, function () use ($prefix) {
            $paths = [];
            foreach (self::collect_classes() as $class) {
                $reflect = new ReflectionClass($prefix.$class);
                foreach ($reflect->getMethods() ?? [] as $method) {
                    foreach ($method->getAttributes() ?? [] as $attr) {
                        if ($attr->getName() == 'App\Decorators\ApiPathfinder') {
                            $paths[] = $attr->getArguments()[0];
                        }
                    }
                }
            }

            return $paths;
        });
    }
}
