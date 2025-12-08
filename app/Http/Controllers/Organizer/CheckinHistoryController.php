<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;

class CheckinHistoryController extends Controller
{
    public function index(Request $request)
    {
        $events = auth()->user()->events()->pluck('id');

        $checkins = Registration::with(['event','user'])
            ->whereIn('event_id', $events)
            ->where('status', 'checked_in')
            ->latest('updated_at')
            ->paginate(10);

        return view('organizer.checkin-history', compact('checkins'));
    }
}