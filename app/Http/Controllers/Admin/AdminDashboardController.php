<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $kpi = [
            'users'          => User::count(),
            'organizers'     => User::role('organizer')->count(),
            'events_active'  => Event::where('status', '!=', 'cancelled')->count(),
            'events_pending' => Event::where('review_status', 'pending')->count(),
            'applied'        => Registration::where('status', 'applied')->count(),
            'approved'       => Registration::where('status', 'approved')->count(),
        ];

        $latestEvents = Event::with(['organizer', 'category'])
            ->latest()
            ->take(5)
            ->get();

        $latestUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact('kpi', 'latestEvents', 'latestUsers'));
    }
}
