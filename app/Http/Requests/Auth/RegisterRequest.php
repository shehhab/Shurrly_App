<?php

namespace App\Http\Requests\Auth;

use App\Models\Seeker;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;


class RegisterRequest extends Request
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
            'name'=>['required','string','min:3','max:25'],
            'email'=>['required','string','email','max:255','unique:' . Seeker::class,
            function ($attribute, $value, $fail) {
                if (!Str::contains($value, '.')) {
                    $fail($attribute.' must be a valid email address from .com');
                }
                $parts = explode('@', $value);
                $localPart = $parts[0];
                // Check if local part consists only of digits
                if (ctype_digit($localPart)) {
                    $fail($attribute.' must be a valid email address with characters other than digits before the @ symbol');
                }
    }],


            'password' => ['required',Rules\Password::defaults(), new \App\Rules\StrongPassword 

],




            'date_birth' => ['required','date_format:d/m/Y', 'before_or_equal:' . now()->subYears(16)->format('d/m/Y'),'after_or_equal:' . now()->subYears(70)->format('d/m/Y')],
            'image'=>['sometimes','image','mimes:jpg,jpeg,png,webp,gif','min:50','max:8000']
        ];
    }


}
