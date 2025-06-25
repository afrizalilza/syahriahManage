<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PendingApprovalMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'pending') {
            return redirect()->route('pending.approval');
        }

        return $next($request);
    }
}
