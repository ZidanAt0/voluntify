<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function index(Request $request)
    {
        $events = $request->user()->bookmarkedEvents()->with('category')->latest('bookmarks.created_at')->paginate(12);
        return view('bookmarks.index', compact('events'));
    }

    public function store(Request $request, Event $event)
    {
        $request->user()->bookmarks()->firstOrCreate(['event_id' => $event->id]);
        return back()->with('status','Event disimpan.');
    }

    public function destroy(Request $request, Event $event)
    {
        $request->user()->bookmarks()->where('event_id', $event->id)->delete();
        return back()->with('status','Bookmark dihapus.');
    }
}
