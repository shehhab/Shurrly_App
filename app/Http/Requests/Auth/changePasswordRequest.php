<?php

namespace App\Http\Requests\Auth;

use Illuminate\Validation\Rules;
use Illuminate\Foundation\Http\FormRequest;

class changePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'current_password' => 'required',
            'new_password' => ['required',Rules\Password::defaults()],
            'confirm_password' => 'required|same:new_password',
        ];
    }
}
