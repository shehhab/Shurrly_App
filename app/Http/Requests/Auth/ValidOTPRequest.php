<?php

namespace App\Http\Requests\Auth;

use App\Traits\AuthResponse;
use Illuminate\Foundation\Http\FormRequest;

class ValidOTPRequest extends FormRequest
{
      /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return  true ;
    }
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => 'required',
            'otp'=>['required','max:4'],
        ];
    }



}
