<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizerVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Cek apakah user memiliki role organizer
        if ($user && $user->hasRole('organizer')) {
            // Cek apakah organizer sudah diverifikasi
            if (is_null($user->organizer_verified_at)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Akun organizer Anda belum diverifikasi oleh admin. Silakan tunggu verifikasi atau hubungi admin.');
            }
        }

        return $next($request);
    }
}
