<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DomainRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(file_exists(storage_path() . "/installed")){
            $uri = url()->full();
            $segments = explode('/', str_replace(''.url('').'', '', $uri));
            $segments = $segments[1] ?? null;
            
            if($segments != null) {
                $local = parse_url(config('app.url'))['host'];
                // Get the request host
                $remote = request()->getHost();
                // Get the remote domain
                
                // remove WWW
                $remote = str_replace('www.', '', $remote);
                
                if ($local != $remote){
                    $domain = \App\Models\CustomDomainRequest::where('status','1')->where('custom_domain',$remote)->first();
                    // If the domain exists
                    if(isset($domain) && !empty($domain)) {
                        $store = \App\Models\Store::find($domain->store_id);
                        if($store && $store->enable_domain == 'on' && $store['domain_switch'] == 'on') {
                            return $next($request);
                        }
                    } else {
                        $sub_store = \App\Models\Store::where('subdomain', '=', $remote)->where('enable_subdomain', 'on')->first();
                        if ($sub_store && $sub_store->enable_subdomain == 'on') {
                            return $next($request);
                        } else {
                            return abort('404', 'Not Found');
                        }
                    }
                }
            }
            return $next($request);
        }else{
            return abort('404', 'Not Found');
        }
    }
}
