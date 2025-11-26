<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    // =========================
    // TAMPILKAN LIST EVENT ORGANIZER
    // =========================
    public function index()
    {
        $events = Event::where('organizer_id', auth()->id())
            ->latest()
            ->get();

        return view('organizer.events.index', compact('events'));
    }

    // =========================
    // FORM TAMBAH EVENT
    // =========================
    public function create()
    {
        $categories = Category::all();
        return view('organizer.events.create', compact('categories'));
    }

    // =========================
    // SIMPAN EVENT (INI YANG TADI ERROR REDIRECT)
    // =========================
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'excerpt'        => 'nullable|string',
            'description'    => 'nullable|string',

            'location_type' => 'required|in:onsite,online,hybrid',
            'city'          => 'nullable|string|max:100',
            'address'       => 'nullable|string|max:255',

            'starts_at'     => 'required|date',
            'ends_at'       => 'required|date|after:starts_at',

            'capacity'      => 'nullable|integer|min:1',
            'status'        => 'required|in:draft,published,closed,cancelled',

            'category_id'   => 'nullable|exists:categories,id',
            'banner_path'   => 'nullable|string',
        ]);

        // WAJIB SESUAI DB
        $data['organizer_id'] = auth()->id();
        $data['slug'] = Str::slug($data['title']) . '-' . uniqid();
        $data['published_at'] = $data['status'] === 'published'
            ? now()
            : null;

        Event::create($data);

        // ✅ INI YANG MEMPERBAIKI MASALAHMU
        return redirect()->route('organizer.events.index')
            ->with('success', 'Event berhasil dibuat!');
    }
}