<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Registration;
use App\Models\Bookmark;

class UserDashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        // KPI status pendaftaran
        $regCounts = Registration::selectRaw('status, COUNT(*) as total')
            ->where('user_id', $userId)
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalRegs     = $regCounts->sum() ?: 0;
        $approvedCount = $regCounts->get('approved', 0);
        $appliedCount  = $regCounts->get('applied', 0);

        // Pendaftaran mendatang (berdasar starts_at event)
        $upcomingRegs = Registration::with(['event.category'])
            ->where('user_id', $userId)
            ->whereHas('event', fn($q) => $q->whereNotNull('starts_at')->where('starts_at','>=',now()))
            ->get()
            ->sortBy(fn($r) => $r->event?->starts_at)
            ->take(5)
            ->values();

        // Pendaftaran terbaru (activity)
        $recentRegs = Registration::with(['event.category'])
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        // Bookmark terbaru
        $bookmarkedEvents = $request->user()->bookmarkedEvents()
            ->with('category')
            ->latest('bookmarks.created_at')
            ->take(6)
            ->get();

        return view('user.dashboard', compact(
            'totalRegs','approvedCount','appliedCount',
            'upcomingRegs','recentRegs','bookmarkedEvents'
        ));
    }
}
