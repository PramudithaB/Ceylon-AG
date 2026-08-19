<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return ($this->user()?->isAdmin() || $this->user()?->hasAnyRole(['Super Admin', 'Admin'])) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $clientId = $this->route('client')?->id ?? $this->route('client');

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'business_name' => ['required', 'string', 'max:255'],
            'nic' => ['required', 'string', 'max:20', Rule::unique('users', 'nic')->ignore($clientId), 'regex:/^([0-9]{9}[vVxX]|[0-9]{12})$/'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($clientId)],
            'address' => ['required', 'string', 'max:500'],
            'district' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'status' => ['required', 'string', 'in:pending,approved,active,deactivated,rejected'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    /**
     * Custom messages.
     */
    public function messages(): array
    {
        return [
            'nic.regex' => 'Please enter a valid Sri Lankan NIC number (e.g. 912345678V or 199123456789).',
        ];
    }
}
