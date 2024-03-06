<?php

namespace App\Http\Requests\Auth;

use Illuminate\Validation\Rules;

class LoginRequest extends Request
{

    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'email' => ['required','string','email'],
            'password' => ['required', Rules\Password::defaults()],
        ];
    }


}
