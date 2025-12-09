<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function index()
    {
        return view('organizer.checkin.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'checkin_code' => 'required|string'
        ]);

        $registration = Registration::where('checkin_code', $request->checkin_code)
            ->with('event', 'user')
            ->first();

        if (!$registration) {
            return back()->with('error', 'Kode check-in tidak ditemukan.');
        }
        if ($registration->event->organizer_id !== auth()->id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk check-in peserta event ini. Event ini bukan milik Anda.');
        }

        if ($registration->checked_in_at) {
            return back()->with('error', 'Peserta sudah melakukan check-in pada: ' . $registration->checked_in_at->format('d M Y H:i'));
        }

        // Validasi status registration
        if ($registration->status !== 'approved') {
            return back()->with('error', 'Peserta belum disetujui. Status: ' . ucfirst($registration->status));
        }

        // ✅ CHECK-IN SAH
        $registration->update([
            'checked_in_at' => now(),
            'status' => 'checked_in',
        ]);

        return back()->with('success', 'Check-in berhasil! Peserta: ' . $registration->user->name . ' untuk event: ' . $registration->event->title);
    }
}
