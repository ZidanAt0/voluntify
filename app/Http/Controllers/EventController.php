<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index(Request $req)
    {
        $q         = $req->string('q')->toString();
        $category  = $req->string('category')->toString(); // slug
        $city      = $req->string('city')->toString();
        $from      = $req->date('date_from');
        $to        = $req->date('date_to');
        $status    = $req->string('status')->toString(); // open|closed

        $events = Event::with('category')
            ->where('status', 'published')
            ->when($q, function ($query, $q) {
                // Postgres ILIKE
                return $query->where(function ($qq) use ($q) {
                    $qq->whereRaw('title ILIKE ?', ["%{$q}%"])
                       ->orWhereRaw('excerpt ILIKE ?', ["%{$q}%"]);
                });
            })
            ->when($category, function ($query, $slug) {
                $query->whereHas('category', fn($c) => $c->where('slug', $slug));
            })
            ->when($city, fn($query, $city) =>
                $query->whereRaw('city ILIKE ?', ["%{$city}%"])
            )
            ->when($from, fn($query, $d) => $query->whereDate('starts_at', '>=', $d))
            ->when($to, fn($query, $d) => $query->whereDate('starts_at', '<=', $d))
            ->when($status === 'open', function ($query) {
                $query->where('ends_at', '>', now())
                      ->where(function ($qq) {
                          $qq->whereNull('capacity')
                             ->orWhereColumn('registration_count', '<', 'capacity');
                      });
            })
            ->when($status === 'closed', function ($query) {
                $query->where(function ($qq) {
                    $qq->where('ends_at', '<=', now())
                       ->orWhere(function ($q2) {
                           $q2->whereNotNull('capacity')
                              ->whereColumn('registration_count', '>=', 'capacity');
                       })
                       ->orWhere('status', 'closed');
                });
            })
            ->orderBy('starts_at')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('events.index', compact('events','categories'));
    }

    public function show(string $slug)
    {
        $event = Event::with(['category','organizer'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('events.show', compact('event'));
    }
}

