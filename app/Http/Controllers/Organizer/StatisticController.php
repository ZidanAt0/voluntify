<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function index()
    {
        $organizerId = auth()->id();

        // Semua event milik organizer
        $events = Event::where('organizer_id', $organizerId)->pluck('id');

        // KPI
        $totalEvents     = Event::where('organizer_id', $organizerId)->count();
        $totalRegistrants= Registration::whereIn('event_id', $events)->count();
        $totalApproved   = Registration::whereIn('event_id', $events)
                                ->where('status','approved')->count();
        $totalCheckedIn  = Registration::whereIn('event_id', $events)
                                ->where('status','checked_in')->count();
        $totalCancelled  = Registration::whereIn('event_id', $events)
                                ->where('status','cancelled')->count();

        // Statistik per event
        $eventStats = Event::where('organizer_id', $organizerId)
            ->withCount([
                'registrations',
                'registrations as approved_count' => fn($q) => $q->where('status','approved'),
                'registrations as checkin_count'  => fn($q) => $q->where('status','checked_in'),
            ])
            ->latest()
            ->get();

        return view('organizer.statistics', compact(
            'totalEvents',
            'totalRegistrants',
            'totalApproved',
            'totalCheckedIn',
            'totalCancelled',
            'eventStats'
        ));
    }
}
