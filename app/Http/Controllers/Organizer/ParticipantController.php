<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;

class ParticipantController extends Controller
{
    // LIST PESERTA EVENT
    public function index(Event $event)
    {
        // proteksi hanya organizer pemilik event
        if ($event->organizer_id !== auth()->id()) abort(403);

        $registrations = Registration::with('user')
            ->where('event_id', $event->id)
            ->latest()
            ->get();

        return view('organizer.participants.index', compact('event', 'registrations'));
    }

    public function approve(Registration $registration)
{
    if ($registration->event->organizer_id !== auth()->id()) abort(403);

    $registration->update([
        'status' => 'approved',
        'checkin_code' => \Str::uuid(), // kode unik untuk QR
    ]);

    return back()->with('success', 'Peserta disetujui & QR dibuat');
}

    // REJECT
    public function reject(Registration $registration)
    {
        if ($registration->event->organizer_id !== auth()->id()) abort(403);

        $registration->update(['status' => 'rejected']);

        return back()->with('success', 'Peserta ditolak');
    }
}
