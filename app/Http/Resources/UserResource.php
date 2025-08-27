<?php

// app/Http/Resources/UserResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'user_id'       => $this->user_id,
            'user_name'     => $this->user_name,
            'user_full_name'=> $this->user_full_name,
            'role_id'       => $this->role_id,
            'created_at'    => $this->user_created,
            'updated_at'    => $this->user_updated,
        ];
    }
}
