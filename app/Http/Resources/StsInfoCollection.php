<?php

// app/Http/Resources/StsInfoCollection.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class StsInfoCollection extends ResourceCollection
{
    public function toArray($request)
    {
        $p = $this->resource; // paginator
        return [
            'items' => StsInfoResource::collection($p->items()),
            'pagination' => [
                'current_page' => $p->currentPage(),
                'per_page'     => $p->perPage(),
                'total'        => $p->total(),
                'last_page'    => $p->lastPage(),
                'has_more'     => $p->hasMorePages(),
            ],
        ];
    }
}
