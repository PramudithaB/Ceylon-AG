<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        $user = $request->user();

        if (! $user->hasVerifiedEmail()) {
            return view('auth.verify-email');
        }

        if ($user->isAdmin() || $user->hasRole('Super Admin') || $user->hasRole('Admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isRef() || $user->hasRole('Ref')) {
            return redirect()->route('ref.dashboard');
        }

        return redirect()->route('dashboard');
    }
}
