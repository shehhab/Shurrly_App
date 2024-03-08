<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class ResetPasswordResource extends JsonResource
{
    public function toArray($request): array
    {
        // Format date_birth using Carbon
        $this->tokens()->delete();
        return [
            'token' => $this->createToken('auth_token')->plainTextToken,
        ];
    }
}
