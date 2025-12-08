<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $q    = $request->query('q');
        $role = $request->query('role');

        $users = User::query()
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when($role, function ($query) use ($role) {
                $query->whereHas('roles', fn($r) => $r->where('name', $role));
            })
            ->with('roles')
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        // Statistics for dashboard
        $stats = [
            'total' => User::count(),
            'organizers' => User::role('organizer')->count(),
            'verified' => User::whereNotNull('organizer_verified_at')->count(),
            'suspended' => User::whereNotNull('suspended_at')->count(),
        ];

        return view('admin.users.index', compact('users', 'q', 'role', 'stats'));
    }

    public function verifyOrganizer(User $user)
    {
        if (!$user->hasRole('organizer')) {
            $user->assignRole('organizer');
        }
        $user->organizer_verified_at = Carbon::now();
        $user->save();

        return back()->with('success', 'Organizer berhasil diverifikasi.');
    }

    public function suspend(User $user)
    {
        $user->update(['suspended_at' => Carbon::now()]);
        return back()->with('success', 'User disuspend.');
    }

    public function unsuspend(User $user)
    {
        $user->update(['suspended_at' => null]);
        return back()->with('success', 'User diaktifkan kembali.');
    }

    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => 'required|in:user,organizer,admin',
        ]);

        if (auth()->id() === $user->id && $data['role'] !== 'admin') {
            return back()->with('error', 'Tidak bisa menurunkan peran admin untuk akun sendiri.');
        }

        $user->syncRoles([$data['role']]);

        // Auto-verify email untuk admin
        if ($data['role'] === 'admin' && is_null($user->email_verified_at)) {
            $user->email_verified_at = Carbon::now();
            $user->save();
        }

        return back()->with('success', 'Peran pengguna diperbarui.');
    }
}
