<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        $request->session()->forget('url.intended');

        // Clear Spatie permission cache to ensure fresh role evaluation
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $user = Auth::user();

        if ($user?->isAdmin() || $user?->hasRole('Super Admin') || $user?->hasRole('Admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user?->isRef() || $user?->hasRole('Ref')) {
            return redirect()->route('ref.dashboard');
        }

        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
