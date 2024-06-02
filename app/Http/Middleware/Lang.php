<?php

namespace App\Http\Middleware;

use App\Models\LandingPageSections;
use App\Models\Utility;
use Closure;
use Illuminate\Http\Request;

class Lang
{
    use \RachidLaasri\LaravelInstaller\Helpers\MigrationsHelper;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(\Auth::check())
        {
           $user=\Auth::user()->lang;
            
            \App::setLocale($user);
           
        }

       
        return $next($request);
    }
}
