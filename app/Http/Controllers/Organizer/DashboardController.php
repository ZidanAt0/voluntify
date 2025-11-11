<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('organizer.dashboard');
    }
}
