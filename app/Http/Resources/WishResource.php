<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WishResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'wishes_id'   => $this->wishes_id,
            'wishes_sender'  => $this->wishes_sender,
            'wishes_account' => $this->wishes_account,
            'wishes'      => $this->wishes,
            'sts_id'      => $this->sts_id,
            'created_at'  => optional($this->wishes_created)->toIso8601String(),
            'updated_at'  => optional($this->wishes_updated)->toIso8601String(),
        ];
    }
}
