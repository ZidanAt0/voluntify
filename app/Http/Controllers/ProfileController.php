<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\RegistrationApplyRequest;




class ProfileController extends Controller
{
    /**
     * Display the user's profile form.  
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    
    public function update(ProfileUpdateRequest $request): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->only(['name','email','whatsapp','city','address','bio']));

        $interests = collect(explode(',', (string) $request->input('interests')))
                        ->map(fn($s)=>trim($s))->filter()->values()->all();
        $user->interests = $interests ?: null;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // upload avatar
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');

            // hapus avatar lama jika ada
            if ($user->avatar_path && \Storage::disk('public')->exists($user->avatar_path)) {
            \Storage::disk('public')->delete($user->avatar_path);
            }


            $user->avatar_path = $path;
        }

        $user->save();
        return back()->with('status','Profil berhasil diperbarui.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    

}
