<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

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

    public function complete(Registration $registration)
    {
        // ✅ 1. Pastikan hanya organizer pemilik event
        if ($registration->event->organizer_id !== auth()->id()) {
            abort(403);
        }

        // ✅ 2. WAJIB SUDAH CHECK-IN
        if ($registration->status !== 'checked_in') {
            return back()->with('error', 'Peserta belum check-in. Tidak bisa diselesaikan.');
        }

        // ✅ 2b. Pastikan library PDF tersedia
        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return back()->with('error', 'Fitur sertifikat belum aktif (package barryvdh/laravel-dompdf belum terpasang).');
        }

        // ✅ 3. Update ke completed
        $registration->update([
            'status' => 'completed',
        ]);

        // ✅ 4. Generate sertifikat PDF
        $pdf = Pdf::loadView('certificates.template', [
            'registration' => $registration
        ]);

        $filename = 'certificate-' . $registration->id . '.pdf';
        $path = 'certificates/' . $filename;

        Storage::put('public/' . $path, $pdf->output());

        // ✅ 5. Simpan path sertifikat
        $registration->update([
            'certificate_path' => $path
        ]);

        return back()->with('success', '✅ Peserta diselesaikan & sertifikat berhasil dibuat');
    }
}
