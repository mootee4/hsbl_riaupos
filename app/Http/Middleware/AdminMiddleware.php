<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login dulu.');
        }

        $user = Auth::user();

        // Pastikan properti name ada dan sesuai
        if (isset($user->name) && $user->name === 'Admin') {
            return $next($request);
        }

        return redirect()->route('login')->with('error', 'Kamu tidak punya akses.');
    }
}
