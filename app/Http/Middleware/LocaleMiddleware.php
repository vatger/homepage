<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('api/*')) {
            $locale = 'de';
            if ($request->hasHeader('x-locale')) {
                $locale = $request->header('x-locale');
            }
            app()->setLocale($locale);
            setlocale(LC_TIME, $locale);
            Carbon::setLocale($locale);

            return $next($request);
        }

        if (! Session::has('language') && ! Auth::check()) {
            Session::put('language', app()->getLocale());
        }
        if (! Session::has('language') && Auth::check()) {
            Session::put('language', Auth::user()->settings->language);
        }

        app()->setLocale(Session::get('language'));
        setlocale(LC_TIME, Session::get('language'));
        Carbon::setLocale(Session::get('language'));

        $response = $next($request);
        $response->headers->set('x-locale', Session::get('language'));

        return $response;
    }
}
