<?php

namespace App\Multitenancy;

use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\TenantFinder\TenantFinder;

class CustomTenantFinder extends TenantFinder
{
    public function findForRequest(Request $request): ?Tenant
    {
        $user = $request->user();
        $tenantId = optional($user)->tenant_id;

        return \App\Models\Tenant::find($tenantId);
    }
}
