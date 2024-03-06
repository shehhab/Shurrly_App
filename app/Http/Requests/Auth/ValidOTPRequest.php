<?php

namespace App\Http\Requests\Auth;

use App\Traits\AuthResponse;
use Illuminate\Foundation\Http\FormRequest;

class ValidOTPRequest extends Request
{
      /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user() ? true : false;
    }
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'otp'=>['required','max:4'],
        ];
    }



}
