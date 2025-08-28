<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login dulu.');
        }

        $user = Auth::user();

        // Cek apakah role user ada di daftar role yang diperbolehkan
        if (!in_array($user->role, $roles)) {
            return redirect()->route('login')->with('error', 'Kamu tidak punya akses.');
        }

        return $next($request);
    }
}
