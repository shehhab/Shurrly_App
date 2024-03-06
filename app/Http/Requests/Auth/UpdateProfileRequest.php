<?php

namespace App\Http\Requests\Auth;
use App\Models\Seeker;

use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'min:3', 'max:25'],
            //'email' => ['sometimes', 'email', 'unique:seekers,email,' . $seeker->id],
            'date_birth' => ['sometimes', 'date_format:d/m/Y', 'before_or_equal:' . now()->subYears(16)->format('d/m/Y'), 'after_or_equal:' . now()->subYears(70)->format('d/m/Y')],
            'image' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'min:50', 'max:8000']

        ];
    }
}
