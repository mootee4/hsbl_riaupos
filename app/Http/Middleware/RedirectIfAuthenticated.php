<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Jika sudah login, redirect ke halaman home
        if (Auth::check()) {
            return redirect('admin.dashboard');  // Sesuaikan dengan URL yang diinginkan
        }

        return $next($request);
    }
}
