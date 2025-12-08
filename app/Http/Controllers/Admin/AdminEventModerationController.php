<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminEventModerationController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['organizer', 'category']);

        // Search by title or organizer name
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('organizer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by review status
        if ($request->filled('review_status')) {
            $query->where('review_status', $request->review_status);
        }

        // Filter by event status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $events = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $categories = \App\Models\Category::orderBy('name')->get();

        return view('admin.events.moderation.index', compact('events', 'categories'));
    }

    public function show(Event $event)
    {
        $event->load(['organizer', 'category']);
        return view('admin.events.moderation.show', compact('event'));
    }

    public function approve(Request $request, Event $event)
    {
        $event->update([
            'review_status' => 'approved',
            'reviewed_by_id' => $request->user()->id,
            'approved_at' => Carbon::now(),
            'rejected_at' => null,
            'status' => 'published',
            'published_at' => Carbon::now(),
        ]);

        return back()->with('success', 'Event disetujui dan dipublikasikan.');
    }

    public function reject(Request $request, Event $event)
    {
        $event->update([
            'review_status' => 'rejected',
            'reviewed_by_id' => $request->user()->id,
            'rejected_at' => Carbon::now(),
            'status' => 'draft',
            'published_at' => null,
        ]);

        return back()->with('success', 'Event ditolak.');
    }

    public function close(Event $event)
    {
        $event->update(['status' => 'closed']);
        return back()->with('success', 'Pendaftaran event ditutup.');
    }

    public function open(Event $event)
    {
        $event->update(['status' => 'published']);
        return back()->with('success', 'Pendaftaran event dibuka kembali.');
    }

    public function cancel(Event $event)
    {
        $event->update(['status' => 'cancelled']);
        return back()->with('success', 'Event dibatalkan.');
    }
}
