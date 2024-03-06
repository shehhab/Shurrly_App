<?php

namespace App\Http\Requests\Auth;

use App\Traits\ResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use  \Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class Request  extends FormRequest
{
    use ResponseTrait;

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            $errors = (new ValidationException($validator))->errors();
            throw new HttpResponseException(
                $this->handleResponse(status: false, message: 'validation error', code: Response::HTTP_UNPROCESSABLE_ENTITY, isError: true, errors: $validator->errors())
            );
        }

        parent::failedValidation($validator);
    }
}
