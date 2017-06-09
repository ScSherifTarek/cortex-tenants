<?php

declare(strict_types=1);

namespace Cortex\Tenantable\Http\Middleware;

use Closure;
use Cortex\Tenantable\Models\Tenant;

class Tenantable
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (is_string($request->route('tenant')) && $request->route('tenant').'.'.domain() === $request->getHost() && ! $tenant = Tenant::where('slug', $tenantSlug = $request->route('tenant'))->first()) {
            return intend([
                'url' => route('frontend.home'),
                'with' => ['warning' => trans('cortex/tenantable::messages.tenant.not_found', ['tenantSlug' => $tenantSlug])],
            ]);
        }

        return $next($request);
    }
}
