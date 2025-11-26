<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Str;

class EventController extends Controller
{
    // ✅ TAMPILKAN SEMUA EVENT MILIK ORGANIZER
    public function index()
    {
        $events = Event::where('organizer_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('organizer.events.index', compact('events'));
    }

    // ✅ FORM TAMBAH EVENT
    public function create()
    {
        return view('organizer.events.create');
    }

    // ✅ SIMPAN EVENT BARU
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable',
            'city'        => 'nullable|string',
            'address'     => 'nullable|string',
            'starts_at'   => 'required|date',
            'ends_at'     => 'required|date|after:starts_at',
            'capacity'    => 'nullable|integer',
        ]);

        Event::create([
            'organizer_id' => auth()->id(),
            'title'        => $request->title,
            'slug'         => Str::slug($request->title).'-'.uniqid(),
            'description' => $request->description,
            'city'         => $request->city,
            'address'     => $request->address,
            'starts_at'   => $request->starts_at,
            'ends_at'     => $request->ends_at,
            'capacity'    => $request->capacity,
            'status'      => 'draft',
        ]);

        return redirect()
            ->route('organizer.events.index')
            ->with('success', 'Event berhasil dibuat.');
    }

    // ✅ FORM EDIT
    public function edit(Event $event)
    {
        abortIf($event->organizer_id !== auth()->id(), 403);

        return view('organizer.events.edit', compact('event'));
    }

    // ✅ UPDATE EVENT
    public function update(Request $request, Event $event)
    {
        abortIf($event->organizer_id !== auth()->id(), 403);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable',
            'city'        => 'nullable|string',
            'address'     => 'nullable|string',
            'starts_at'   => 'required|date',
            'ends_at'     => 'required|date|after:starts_at',
            'capacity'    => 'nullable|integer',
        ]);

        $event->update($request->all());

        return redirect()
            ->route('organizer.events.index')
            ->with('success', 'Event berhasil diupdate.');
    }

    // ✅ HAPUS EVENT
    public function destroy(Event $event)
    {
        abortIf($event->organizer_id !== auth()->id(), 403);

        $event->delete();

        return back()->with('success', 'Event berhasil dihapus.');
    }
}
