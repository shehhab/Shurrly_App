<?php

namespace App\Http\Requests\Auth;

use App\Traits\AuthResponse;
use Illuminate\Foundation\Http\FormRequest;

class ResendOTPCodeRequest extends Request
{
    public function authorize(): bool
    {
        return true;

        //return auth()->user() ? true :false;
    }
    public function rules(): array
    {
        return [
            'email'=>['required','string','email','exists:seekers,email']
        ];
    }


}
