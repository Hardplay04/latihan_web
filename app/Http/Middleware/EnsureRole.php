<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRole
{
    /**
     * Usage: ->middleware('role:admin') or ->middleware('role:admin,instructor')
     */
    public function handle(Request $request, Closure $next, string $roles)
    {
        $user = $request->user();

        if (!$user) {
            abort(401);
        }

        $allowed = collect(explode(',', $roles))
            ->map(fn ($r) => trim($r))
            ->filter()
            ->contains($user->role);

        if (!$allowed) {
            abort(403, 'Forbidden.');
        }

        return $next($request);
    }
}
