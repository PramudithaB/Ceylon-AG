<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $provinces = \App\Support\Locations::getProvinces();
        $districts = \App\Support\Locations::getAllDistricts();
        $provinceDistricts = \App\Support\Locations::getHierarchy();

        return view('auth.register', compact('provinces', 'districts', 'provinceDistricts'));
    }

    /**
     * Handle an incoming client registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'business_name' => ['required', 'string', 'max:255'],
            'nic' => ['required', 'string', 'max:20', 'unique:'.User::class, 'regex:/^([0-9]{9}[vVxX]|[0-9]{12})$/'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'address' => ['required', 'string', 'max:500'],
            'district' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'nic.regex' => 'Please enter a valid Sri Lankan NIC number (e.g. 912345678V or 199123456789).',
        ]);

        if (! \App\Support\Locations::isValidPair($request->province, $request->district)) {
            throw ValidationException::withMessages([
                'district' => "The selected district '{$request->district}' does not belong to the selected province '{$request->province}'.",
            ]);
        }

        $fullName = "{$request->first_name} {$request->last_name}";

        $user = User::create([
            'name' => $fullName,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'business_name' => $request->business_name,
            'nic' => strtoupper($request->nic),
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'district' => $request->district,
            'province' => $request->province,
            'password' => Hash::make($request->password),
            'status' => User::STATUS_PENDING,
            'role' => User::ROLE_CLIENT,
        ]);

        // Ensure Client role exists and assign it
        \Spatie\Permission\Models\Role::findOrCreate('Client', 'web');
        $user->assignRole('Client');

        try {
            // Dispatch Registered event (Triggers Email Verification Mail)
            event(new Registered($user));

            // Dispatch Welcome Notification
            $user->notify(new \App\Notifications\ClientRegisteredNotification($user));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed sending registration emails to {$user->email}: " . $e->getMessage(), ['exception' => $e]);
        }

        // Clear intended URL state so subsequent logins start fresh
        $request->session()->forget('url.intended');

        // Redirect to registration pending approval notice page (DO NOT AUTO-LOGIN)
        return redirect()->route('register.success')->with('registered_email', $user->email);
    }
}
