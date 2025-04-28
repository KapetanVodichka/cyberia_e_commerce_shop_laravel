<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'balance' => $this->balance,
            'role' => $this->role,
        ];
    }
}
