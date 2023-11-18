<?php

namespace App\Multitenancy;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\TenantFinder\TenantFinder;

class CustomTenantFinder extends TenantFinder
{
    public function findForRequest(Request $request): ?Tenant
    {
        $path = $request->getHost(); // should return /company1.myapp or /company2.myapp or /myapp
        if (str_contains($path, ".")) { // if path has dot.
            list($subdomain, $main) = explode('.', $path);
//            if(strcmp($domain, $main) !== 0){
//                abort(404); // if domain is not myapp then throw 404 page error
//            }
        } else{
//            if(strcmp($domain, $path) !== 0){
//                abort(404); // if domain is not myapp then throw 404 page error
//            }
            $subdomain = ""; // considering for main domain value is empty string.
        }

        $tenant = \App\Models\Tenant::where('domain', $subdomain)->first(); // if not found then will throw 404
//        dd($subdomain);
//        dd($tenant);

//        $request->session()->put('subdomain', $company); //store it in session

//        return $next($request);

        return $tenant;
    }
}
