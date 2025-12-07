<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminEventModerationController extends Controller
{
    public function index()
    {
        $events = Event::with(['organizer', 'category'])
            ->whereIn('review_status', ['pending', 'rejected'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.events.moderation.index', compact('events'));
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
        ]);

        return back()->with('success', 'Event disetujui.');
    }

    public function reject(Request $request, Event $event)
    {
        $event->update([
            'review_status' => 'rejected',
            'reviewed_by_id' => $request->user()->id,
            'rejected_at' => Carbon::now(),
        ]);

        return back()->with('success', 'Event ditolak.');
    }

    public function close(Event $event)
    {
        $event->update(['status' => 'closed']);
        return back()->with('success', 'Pendaftaran event ditutup.');
    }

    public function cancel(Event $event)
    {
        $event->update(['status' => 'cancelled']);
        return back()->with('success', 'Event dibatalkan.');
    }
}
