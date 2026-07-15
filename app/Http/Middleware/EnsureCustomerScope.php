<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applied to all /api/portal/* routes.
 * Guarantees every authenticated customer user has a company_id,
 * and makes it available to controllers without re-checking each time.
 */
class EnsureCustomerScope
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isCustomer() || ! $user->company_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (! $user->company->is_active) {
            return response()->json(['message' => 'Account suspended'], 403);
        }

        // Available in controllers via $request->attributes->get('company_id')
        $request->attributes->set('company_id', $user->company_id);

        return $next($request);
    }
}
