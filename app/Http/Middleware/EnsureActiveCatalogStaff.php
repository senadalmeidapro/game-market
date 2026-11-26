<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveCatalogStaff
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        abort_unless(
            $user
                && $user->status === 'active'
                && $user->two_factor_confirmed_at !== null
                && $user->hasAnyRole(['super_admin', 'catalog_manager'])
                && $user->can('catalog.manage'),
            403,
        );

        return $next($request);
    }
}
