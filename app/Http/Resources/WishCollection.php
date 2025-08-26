<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection; // <- WAJIB ini, BUKAN Illuminate\Support\Collection
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class WishCollection extends ResourceCollection
{
    public function toArray($request)
    {
        $res = $this->resource;

        if ($res instanceof LengthAwarePaginator) {
            $items = WishResource::collection($res->items())->resolve();
            return [
                'items' => $items,
                'pagination' => [
                    'current_page' => $res->currentPage(),
                    'per_page'     => $res->perPage(),
                    'total'        => $res->total(),
                    'last_page'    => $res->lastPage(),
                    'has_more'     => $res->hasMorePages(),
                ],
            ];
        }

        // fallback kalau yang masuk bukan paginator
        return [
            'items' => WishResource::collection($this->collection)->resolve(),
            'pagination' => null,
        ];
    }

    // hilangkan links/meta default
    protected function paginationInformation($request, $paginated, $default)
    {
        return [];
    }
}

