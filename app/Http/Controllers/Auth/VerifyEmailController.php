<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Mark the user's email address as verified.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        if (! $request->hasValidSignature() && ! $request->hasValidSignature(false)) {
            abort(403, 'Invalid or expired verification link.');
        }

        $user = User::find($request->route('id'));

        if (! $user) {
            abort(404, 'User account not found.');
        }

        if (! hash_equals(sha1($user->getEmailForVerification()), (string) $request->route('hash'))) {
            abort(403, 'Invalid verification hash.');
        }

        if ($user->hasVerifiedEmail()) {
            if (auth()->check()) {
                return $this->redirectForUser($user);
            }

            return redirect()->route('login', ['verified' => 1])
                ->with('status', 'Your email address has already been verified. Please log in.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        if (auth()->check()) {
            return $this->redirectForUser($user);
        }

        return redirect()->route('login', ['verified' => 1])
            ->with('status', 'Your email has been verified successfully. Please log in or wait for administrator approval.');
    }

    protected function redirectForUser(User $user): RedirectResponse
    {
        if ($user->isAdmin() || $user->hasRole('Super Admin') || $user->hasRole('Admin')) {
            return redirect()->route('admin.dashboard', ['verified' => 1]);
        }

        if ($user->isRef() || $user->hasRole('Ref')) {
            return redirect()->route('ref.dashboard', ['verified' => 1]);
        }

        return redirect()->route('dashboard', ['verified' => 1]);
    }
}
