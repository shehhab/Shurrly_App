<?php

namespace App\Http\Requests\chat;

use App\Models\Seeker;
use Illuminate\Foundation\Http\FormRequest;

class StoreChatRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        $userModel = Seeker::class;
        return [
            'seeker_id' => "required|exists:{$userModel},id",
            'name' => "nullable",
            'is_private' => "nullable|boolean",
        ];
    }
}

