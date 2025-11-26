<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;

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
            'description' => 'nullable',
            'starts_at'   => 'required|date',
            'ends_at'     => 'required|date|after_or_equal:starts_at',
            'capacity'    => 'nullable|integer|min:1',
            'banner'      => 'nullable|image|max:2048',
        ]);

        // Upload banner
        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('banners', 'public');
        }

        Event::create([
            'organizer_id' => auth()->id(),
            'category_id'  => null, // optional for now
            'title'        => $request->title,
            'slug'         => Str::slug($request->title).'-'.uniqid(),
            'excerpt'      => Str::limit(strip_tags($request->description), 150),
            'description'  => $request->description,
            'location_type'=> 'onsite',
            'city'         => null,
            'address'      => null,
            'starts_at'    => $request->starts_at,
            'ends_at'      => $request->ends_at,
            'capacity'     => $request->capacity,
            'banner_path'  => $bannerPath,
            'status'       => 'draft',
        ]);

        return redirect()->route('organizer.events.index')
            ->with('success', 'Event berhasil dibuat.');
    }


    public function publish(Event $event)
{
    $event->update([
        'status' => 'published',
        'published_at' => now()
    ]);

    return redirect()
        ->route('organizer.events.index')
        ->with('success', 'Event berhasil dipublish!');
}

public function unpublish(Event $event)
{
    $event->update([
        'status' => 'draft',
        'published_at' => null
    ]);

    return redirect()
        ->route('organizer.events.index')
        ->with('success', 'Event dikembalikan ke draft.');
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
        'description' => 'nullable',
        'starts_at' => 'required|date',
        'ends_at' => 'required|date|after:starts_at',
        'capacity' => 'nullable|integer',
        'category_id' => 'nullable|exists:categories,id',
        'city' => 'nullable|string',
        'address' => 'nullable|string',
    ]);

    $data['slug'] = \Str::slug($data['title']);

    $event->update($data);

    return redirect()
        ->route('organizer.events.index')
        ->with('success', 'Event berhasil diperbarui!');
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
