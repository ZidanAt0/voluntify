<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Http\Requests\RegistrationApplyRequest;


class RegistrationController extends Controller
{

    // Tampilkan form apply
    public function create(Request $request, Event $event)
    {
        // harus event terbuka & kuota ada
        if (!$event->isOpen()) {
            return redirect()->route('events.show', $event->slug)
                ->with('status', 'Pendaftaran ditutup.');
        }

        // kalau sudah terdaftar (kecuali cancelled) langsung ke detail pendaftaran
        $existing = $event->registrations()
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existing && $existing->status !== 'cancelled') {
            return redirect()->route('registrations.show', $existing)
                ->with('status', 'Kamu sudah terdaftar di event ini.');
        }

        return view('registrations.apply', [
            'event' => $event,
            'user'  => $request->user(),
        ]);
    }

    // Simpan apply
    public function store(RegistrationApplyRequest $request, Event $event)
    {
        if (!$event->isOpen()) {
            return back()->with('status', 'Pendaftaran ditutup.');
        }

        // kuota penuh?
        if (!is_null($event->capacity) && $event->registration_count >= $event->capacity) {
            return back()->with('status', 'Kuota peserta sudah penuh.');
        }

        // jika pernah cancelled, aktifkan lagi + overwrite answers
        $existing = Registration::where('event_id', $event->id)
            ->where('user_id', $request->user()->id)
            ->first();

        $answers = $request->validated();
        // "agree" hanya flag, tak perlu disimpan jika tak ingin
        // unset($answers['agree']);

        if ($existing) {
    $existing->update([
        'status'       => 'applied',
        'applied_at'   => now(),
        'cancelled_at' => null,
        'answers'      => $answers,
        'checkin_code' => (string) random_int(100000, 999999),
    ]);

    $event->increment('registration_count');

    return redirect()->route('registrations.show', $existing)
        ->with('status', 'Pendaftaran diaktifkan kembali.');
}


        $reg = Registration::create([
            'event_id' => $event->id,
            'user_id'  => $request->user()->id,
            'status'   => 'applied',
            'applied_at' => now(),
            'answers'  => $answers,
            'checkin_code' => (string) random_int(100000, 999999),
        ]);

        $event->increment('registration_count');

        // (opsional) isi WA/kota user jika kosong
        if (!$request->user()->whatsapp && $request->filled('whatsapp')) {
            $request->user()->forceFill([
                'whatsapp' => $request->input('whatsapp'),
                'city'     => $request->input('city', $request->user()->city),
            ])->save();
        }

        return redirect()->route('registrations.show', $reg)
            ->with('status', 'Berhasil mendaftar. Menunggu persetujuan.');
    }

    // DELETE /me/registrations/{registration}
    public function destroy(Request $request, Registration $registration)
    {
        $this->authorize('own', $registration);

        if (!$registration->cancellable()) {
            return back()->with('status', 'Tidak bisa dibatalkan (sudah mulai / dibatalkan).');
        }

        $registration->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        // kembalikan slot
        $registration->event()->decrement('registration_count');

        return redirect()->route('registrations.index')->with('status', 'Pendaftaran dibatalkan.');
    }

    public function index(Request $request)
    {
        $status = $request->query('status');
        $q      = $request->query('q');

        $regs = \App\Models\Registration::with(['event.category'])
            ->where('user_id', $request->user()->id)
            ->when($status, fn($qq) => $qq->where('status', $status))
            ->when($q, fn($qq) => $qq->whereHas('event', fn($ee) =>
                $ee->where('title', 'like', '%'.$q.'%')
            ))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $counts = \App\Models\Registration::selectRaw('status, COUNT(*) as total')
            ->where('user_id', $request->user()->id)
            ->groupBy('status')
            ->pluck('total','status')
            ->all();

        return view('registrations.index', compact('regs','status','q','counts'));
    }


    // GET /me/registrations/{registration}
    public function show(Request $request, Registration $registration)
    {
        $this->authorize('own', $registration);
        $registration->load('event');
        return view('registrations.show', compact('registration'));
    }
}
