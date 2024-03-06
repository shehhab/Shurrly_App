<?php

namespace App\Http\Requests\Auth;

class ForgetPasswordRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'email'=>['required','email','exists:seekers,email']
        ];
    }


}
