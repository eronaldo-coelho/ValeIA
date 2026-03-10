<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPlano;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPlan
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('web')->check()) {
            $hasPlan = UserPlano::where('admin_id', Auth::id())->exists();
            
            if (!$hasPlan && !$request->routeIs('plan.selection', 'plan.store', 'auth.*', 'logout')) {
                return redirect()->route('plan.selection');
            }
        }

        return $next($request);
    }
}