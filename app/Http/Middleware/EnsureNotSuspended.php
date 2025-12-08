<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureNotSuspended
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !is_null(Auth::user()->suspended_at)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Akun Anda telah di-suspend. Silakan hubungi admin untuk informasi lebih lanjut.');
        }

        return $next($request);
    }
}
