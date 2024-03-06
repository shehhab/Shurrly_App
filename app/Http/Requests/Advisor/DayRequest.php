<?php

namespace App\Http\Requests\Advisor;

use App\Http\Requests\Auth\Request;


class DayRequest extends Request
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
            'days' => ['required','array'],
            'days.*.day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'days.*.from' => 'required|date_format:H:i',
            'days.*.to' => 'required|date_format:H:i|after:from',
            'days.*.break_from' => 'nullable|date_format:H:i|required_with:days.*.break_to',
            'days.*.break_from.required_with' => 'The Break To field is required when Break From is present.', // رسالة الخطأ المخصصة

            'days.*.break_to' => 'nullable|date_format:H:i|required_with:days.*.break_from',

        ];

    }
    public function messages()
{
    return [
        'days.*.break_from.required_with' => 'If Break To is selected, Break From field must also be specified',
        'days.*.break_to.required_with' => 'If Break From is selected, Break To field must also be specified', // رسالة الخطأ المخصصة

    ];
}
}
