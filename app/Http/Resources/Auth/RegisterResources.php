<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $formattedDateOfBirth = optional($this->date_birth)->format('d-m-Y');

        return [
            'name'=>$this->name,
            'email'=>$this->email,
            'date_birth'=>$this->date_birth,
        ];
    }
}
