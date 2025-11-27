<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function index(Request $request)
    {
        $q          = $request->query('q');
        $categoryId = $request->query('category_id');
        $sort       = $request->query('sort', 'new'); // new | soon

        $query = $request->user()
            ->bookmarkedEvents()                         // relasi belongsToMany
            ->with(['category'])                         // untuk badge kategori
            ->when($categoryId, fn($qq) => $qq->where('category_id', $categoryId))
            ->when($q, fn($qq) => $qq->where('title', 'like', '%' . $q . '%'));

        // urutkan
        if ($sort === 'soon') {
            $query->orderBy('starts_at');               // event paling dekat dulu
        } else {
            $query->latest('bookmarks.created_at');     // yang baru dibookmark dulu
        }

        $events = $query->paginate(12)->withQueryString();

        $categories = \App\Models\Category::orderBy('name')->get(['id', 'name']);

        return view('bookmarks.index', compact('events', 'q', 'categoryId', 'sort', 'categories'));
    }


    public function store(Request $request, Event $event)
    {
        $request->user()->bookmarks()->firstOrCreate(['event_id' => $event->id]);
        return back()->with('status', 'Event disimpan.');
    }

    public function destroy(Request $request, Event $event)
    {
        $request->user()->bookmarks()->where('event_id', $event->id)->delete();
        return back()->with('status', 'Bookmark dihapus.');
    }
}
