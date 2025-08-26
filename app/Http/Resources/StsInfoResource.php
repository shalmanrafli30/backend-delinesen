<?php

// app/Http/Resources/StsInfoResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StsInfoResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'sts_id'     => $this->sts_id,
            'sts_name'   => $this->sts_name,
            'created_at' => optional($this->sts_created)->toIso8601String(),
            'updated_at' => optional($this->sts_updated)->toIso8601String(),
        ];
    }
}

