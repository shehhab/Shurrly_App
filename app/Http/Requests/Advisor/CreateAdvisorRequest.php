<?php

namespace App\Http\Requests\Advisor;

use Illuminate\Foundation\Http\FormRequest;

class CreateAdvisorRequest extends FormRequest
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
                'image'=>['required','image','mimes:jpg,jpeg,png,webp,gif','max:8000'],
                'expertise'=>['required','max:255'],
                'certificates'=>['required', 'file', 'mimes:pdf', 'max:50480'],
                'Offere'=>['required'],
                'bio' =>'required|max:255|string',
                'video' => ['required', 'file', 'mimes:mov,pdf,mp4,mp3', 'max:50480'],
                'available' => ['sometimes','boolean'],
                ];
    }
}
