<?php

namespace App\Http\Controllers\Ref;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class RefProfileController extends Controller
{
    /**
     * Display the Ref user's profile edit form.
     */
    public function edit(Request $request)
    {
        return view('ref.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the Ref user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'address' => ['required', 'string', 'max:500'],
            'district' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $validated['profile_photo_path'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        $validated['name'] = "{$validated['first_name']} {$validated['last_name']}";

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->fill($validated);
        $user->save();

        flash_message('Profile updated successfully!', 'success');

        return redirect()->route('ref.profile.edit');
    }

    /**
     * Update the Ref user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        flash_message('Password changed successfully!', 'success');

        return redirect()->route('ref.profile.edit');
    }
}
