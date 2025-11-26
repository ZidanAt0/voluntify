<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Registration;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // ✅ TOTAL EVENT YANG DIBUAT ORGANIZER
        $totalEvents = Event::where('organizer_id', $user->id)->count();

        // ✅ TOTAL PENDAFTAR KE SEMUA EVENT ORGANIZER
        $totalRegistrations = Registration::whereHas('event', function ($q) use ($user) {
            $q->where('organizer_id', $user->id);
        })->count();

        // ✅ EVENT YANG MASIH AKTIF (PUBLISHED)
        $activeEvents = Event::where('organizer_id', $user->id)
            ->where('status', 'published')
            ->count();

        // ✅ EVENT AKAN DATANG
        $upcomingEvents = Event::where('organizer_id', $user->id)
            ->whereNotNull('starts_at')
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at')
            ->take(5)
            ->get();

        // ✅ PENDAFTAR TERBARU KE EVENT ORGANIZER
        $recentRegistrations = Registration::with(['user', 'event'])
            ->whereHas('event', function ($q) use ($user) {
                $q->where('organizer_id', $user->id);
            })
            ->latest()
            ->take(5)
            ->get();

        return view('organizer.dashboard', compact(
            'totalEvents',
            'totalRegistrations',
            'activeEvents',
            'upcomingEvents',
            'recentRegistrations'
        ));
    }
}
