<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('organizer_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('organizer.events.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

    return view('organizer.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|max:255',
            'excerpt'     => 'nullable|string|max:255',
            'description' => 'nullable',
            'category_id' => 'nullable|exists:categories,id',
            'location_type'=> 'required|in:onsite,online,hybrid',
            'city'        => 'nullable|string|max:255',
            'address'     => 'nullable|string|max:255',
            'starts_at'   => 'required|date|after:now',
            'ends_at'     => 'required|date|after:starts_at',
            'capacity'    => 'nullable|integer|min:1',
            'banner'      => 'nullable|image|max:2048',
            'status'      => 'required|in:draft,submit_for_review',
        ]);

        // Upload banner
        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('banners', 'public');
        }

        $excerpt = $request->filled('excerpt')
            ? $request->excerpt
            : Str::limit(strip_tags($request->description), 150);

        // Determine review status based on user choice
        $reviewStatus = null;
        $successMessage = '';

        if ($request->status === 'submit_for_review') {
            $reviewStatus = 'pending';
            $successMessage = 'Event berhasil dibuat dan diajukan untuk review admin.';
        } else {
            $reviewStatus = null;
            $successMessage = 'Event berhasil disimpan sebagai draft. Anda bisa submit untuk review nanti.';
        }

        Event::create([
            'organizer_id' => auth()->id(),
            'category_id'  => $request->category_id,
            'title'        => $request->title,
            'slug'         => Str::slug($request->title).'-'.uniqid(),
            'excerpt'      => $excerpt,
            'description'  => $request->description,
            'location_type'=> $request->location_type,
            'city'         => $request->city,
            'address'      => $request->address,
            'starts_at'    => $request->starts_at,
            'ends_at'      => $request->ends_at,
            'capacity'     => $request->capacity,
            'banner_path'  => $bannerPath,
            'status'       => 'draft',
            'review_status' => $reviewStatus,
        ]);

        return redirect()->route('organizer.events.index')
            ->with('success', $successMessage);
    }


    public function publish(Event $event)
{
    if ($event->organizer_id !== auth()->id()) abort(403);

    // Hanya bisa submit jika status draft
    if ($event->status !== 'draft') {
        return redirect()
            ->route('organizer.events.index')
            ->with('error', 'Hanya event dengan status draft yang bisa diajukan untuk review.');
    }

    // Submit untuk review admin
    $event->update([
        'review_status' => 'pending',
        'rejected_at' => null, // Reset jika sebelumnya ditolak
    ]);

    return redirect()
        ->route('organizer.events.index')
        ->with('success', 'Event telah diajukan untuk review admin.');
}

public function unpublish(Event $event)
{
    if ($event->organizer_id !== auth()->id()) abort(403);

    // Hanya bisa unpublish jika sudah approved
    if ($event->review_status === 'approved') {
        $event->update([
            'status' => 'draft',
            'published_at' => null,
        ]);

        return redirect()
            ->route('organizer.events.index')
            ->with('success', 'Event dikembalikan ke draft.');
    }

    return redirect()
        ->route('organizer.events.index')
        ->with('error', 'Hanya event yang sudah disetujui yang bisa di-unpublish.');
}

    public function edit(Event $event)
{
    if ($event->organizer_id !== auth()->id()) abort(403);

    $categories = \App\Models\Category::all();

    return view('organizer.events.edit', compact('event', 'categories'));
}


    public function update(Request $request, Event $event)
{
    if ($event->organizer_id !== auth()->id()) abort(403);

    $data = $request->validate([
        'title' => 'required|string',
        'excerpt' => 'nullable|string|max:255',
        'description' => 'nullable',
        'starts_at' => 'required|date|after:now',
        'ends_at' => 'required|date|after:starts_at',
        'capacity' => 'nullable|integer|min:1',
        'category_id' => 'nullable|exists:categories,id',
        'city' => 'nullable|string|max:255',
        'address' => 'nullable|string|max:255',
        'location_type' => 'required|in:onsite,online,hybrid',
        'status' => 'required|in:draft,published,closed,cancelled',
        'banner' => 'nullable|image|max:2048',
        'remove_banner' => 'nullable|boolean',
    ]);

    $startsAt = Carbon::parse($data['starts_at']);
    $endsAt = Carbon::parse($data['ends_at']);
    if ($startsAt->isPast() || $endsAt->isPast()) {
        return back()
            ->with('error', 'Event tidak dapat diperbarui karena jadwal sudah lewat.')
            ->withInput();
    }

    // Validasi perubahan status
    $newStatus = $data['status'];
    $currentStatus = $event->status;

    // Organizer tidak bisa publish event jika belum approved admin
    if ($newStatus === 'published' && $event->review_status !== 'approved') {
        return back()
            ->with('error', 'Event belum disetujui admin. Tidak bisa dipublish.')
            ->withInput();
    }

    // Jika mengubah ke cancelled, set published_at ke null dan batalkan semua registrations
    if ($newStatus === 'cancelled' && $currentStatus !== 'cancelled') {
        $data['published_at'] = null;

        // Batalkan SEMUA registrations yang belum completed atau rejected
        // Termasuk: applied, pending, approved, waitlisted, checked_in
        $affectedRegistrations = $event->registrations()
            ->whereNotIn('status', ['completed', 'rejected', 'cancelled'])
            ->update([
                'status' => 'cancelled',
                'cancelled_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
    }

    // Jika mengubah dari cancelled ke published (dan sudah approved)
    if ($newStatus === 'published' && $currentStatus === 'cancelled' && $event->review_status === 'approved') {
        $data['published_at'] = Carbon::now();
    }

    $data['excerpt'] = $data['excerpt'] ?? Str::limit(strip_tags($request->description), 150);
    $data['slug'] = \Str::slug($data['title']).'-'.$event->id;

    // Banner update
    if ($request->boolean('remove_banner') && $event->banner_path) {
        Storage::disk('public')->delete($event->banner_path);
        $data['banner_path'] = null;
    }

    if ($request->hasFile('banner')) {
        if ($event->banner_path) {
            Storage::disk('public')->delete($event->banner_path);
        }
        $data['banner_path'] = $request->file('banner')->store('banners', 'public');
    }

    $event->update($data);

    // Success message berdasarkan perubahan status
    $message = 'Event berhasil diperbarui!';
    if ($newStatus !== $currentStatus) {
        $statusMessages = [
            'draft' => 'Event dikembalikan ke draft.',
            'published' => 'Event berhasil dipublish!',
            'closed' => 'Pendaftaran event berhasil ditutup. Peserta yang sudah terdaftar masih bisa check-in.',
            'cancelled' => 'Event dibatalkan. ' . (isset($affectedRegistrations) && $affectedRegistrations > 0 ? "$affectedRegistrations pendaftaran telah dibatalkan secara otomatis." : 'Semua pendaftaran telah dibatalkan.'),
        ];
        $message = $statusMessages[$newStatus] ?? $message;
    }

    return redirect()
        ->route('organizer.events.index')
        ->with('success', $message);
}


    public function destroy(Event $event)
    {
        $this->authorizeEvent($event);

        if ($event->banner_path) {
            Storage::disk('public')->delete($event->banner_path);
        }

        $event->delete();

        return redirect()->route('organizer.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }

    private function authorizeEvent(Event $event)
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403, 'Tidak punya akses');
        }
    }
}
