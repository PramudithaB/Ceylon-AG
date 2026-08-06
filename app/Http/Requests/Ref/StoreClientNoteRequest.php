<?php

namespace App\Http\Requests\Ref;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isRef() || auth()->user()->hasRole('Ref'));
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'min:2', 'max:2000'],
        ];
    }
}
