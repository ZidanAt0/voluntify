<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;

class DashboardController extends Controller
{
    public function index()
{
    $organizerId = auth()->id();

    $eventIds = Event::where('organizer_id', $organizerId)->pluck('id');

    $totalEvents      = Event::where('organizer_id', $organizerId)->count();
    $totalRegistrants = Registration::whereIn('event_id', $eventIds)->count();
    $totalApproved    = Registration::whereIn('event_id', $eventIds)->where('status','approved')->count();
    $totalCheckedIn   = Registration::whereIn('event_id', $eventIds)->where('status','checked_in')->count();
    $totalCancelled   = Registration::whereIn('event_id', $eventIds)->where('status','cancelled')->count();

    $eventStats = Event::where('organizer_id', $organizerId)
        ->withCount([
            'registrations',
            'registrations as approved_count' => fn($q) => $q->where('status','approved'),
            'registrations as checkin_count'  => fn($q) => $q->where('status','checked_in'),
        ])
        ->latest()
        ->get();

    $upcomingEvents = Event::where('organizer_id', $organizerId)
        ->where('starts_at', '>=', now())
        ->orderBy('starts_at')
        ->take(5)
        ->get();

    $recentRegistrations = Registration::with(['user','event'])
        ->whereIn('event_id', $eventIds)
        ->latest()
        ->take(5)
        ->get();

    return view('organizer.dashboard', compact(
        'totalEvents',
        'totalRegistrants',
        'totalApproved',
        'totalCheckedIn',
        'totalCancelled',
        'eventStats',
        'upcomingEvents',
        'recentRegistrations'
    ));
}

}
