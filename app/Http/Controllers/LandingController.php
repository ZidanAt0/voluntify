<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Statistics
        $stats = [
            'events' => Event::where('status', 'published')
                ->where('review_status', 'approved')
                ->whereDate('ends_at', '>=', now())
                ->count(),
            'volunteers' => User::role('user')->count(),
            'organizers' => User::role('organizer')
                ->whereNotNull('organizer_verified_at')
                ->count(),
        ];

        // Featured events - ambil 4 event terbaru yang published & approved
        $featuredEvents = Event::where('status', 'published')
            ->where('review_status', 'approved')
            ->whereDate('starts_at', '>=', now())
            ->with(['category', 'organizer'])
            ->orderBy('starts_at', 'asc')
            ->take(4)
            ->get();

        // Categories untuk ditampilkan
        $categories = Category::withCount(['events' => function ($query) {
            $query->where('status', 'published')
                ->where('review_status', 'approved');
        }])
            ->orderByDesc('events_count')
            ->get()
            ->filter(function ($category) {
                return $category->events_count > 0;
            })
            ->take(6);

        return view('landing', compact('stats', 'featuredEvents', 'categories'));
    }
}
