<?php

namespace App\Http\Middleware;

use Closure;
use App;
use Auth;

class SetLangMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()) {
            App::setLocale(isset(Auth::user()->metadata->language) ? Auth::user()->metadata->language : 'de');
        } else {
            // default language
            App::setLocale('de');
        }

        return $next($request);
    }
}
