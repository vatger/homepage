<?php

namespace App\Http\Controllers;

use App\Models\Membership\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    protected ?User $current_user;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->current_user = null;
            if (Auth::check() && Auth::guard('web')->check()) {
                $this->current_user = Auth::user();
            }
            return $next($request);
        });
    }

    /**
     *
     * @param string[] $load_missing
     * @return ?User
     */
    protected function user(array $load_missing = []): ?User
    {
        if (!empty($load_missing)) {
            $this->current_user = $this->current_user->loadMissing($load_missing);
        }
        return $this->current_user;
    }
}
