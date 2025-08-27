<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Http\Requests\StoreWishRequest;
use App\Http\Requests\UpdateWishRequest;
use App\Http\Resources\WishCollection;
use App\Http\Resources\WishResource;
use App\Models\StsInfo;
use App\Models\StsInformation;
use App\Models\StsWish;
use Illuminate\Http\Request;

class WishController extends Controller
{
    // GET /api/v1/wishes?sts_id=STS2025&search=alya&page=1&per_page=10
    public function index(Request $request)
    {
        $q = StsWish::query()->orderByDesc('wishes_created');

        if ($sts = $request->string('sts_id')->toString()) {
            $q->where('sts_id', $sts);
        }
        if ($s = $request->string('search')->toString()) {
            $q->where(function ($w) use ($s) {
                $w->where('wishes_sender', 'like', "%{$s}%")
                    ->orWhere('wishes_account', 'like', "%{$s}%")
                    ->orWhere('wishes', 'like', "%{$s}%");
            });
        }

        /** @var LengthAwarePaginator $p */
        $p = $q->paginate($request->integer('per_page', 10))->withQueryString();

        // penting: resolve agar menjadi array biasa (bukan ResourceCollection lagi)
        $items = WishResource::collection($p->items())->resolve();

        return response()->json([
            'data' => [
                'items' => $items,
                'pagination' => [
                    'current_page' => $p->currentPage(),
                    'per_page'     => $p->perPage(),
                    'total'        => $p->total(),
                    'last_page'    => $p->lastPage(),
                    'has_more'     => $p->hasMorePages(),
                ],
            ],
        ]);
    }

    // POST /api/v1/wishes
    public function store(StoreWishRequest $request)
    {
        $data = $request->validated();

        // auto-generate ID jika kosong
        if (empty($data['wishes_id'])) {
            $last = StsWish::where('sts_id', $data['sts_id'])
                ->selectRaw("MAX(CAST(SUBSTRING_INDEX(wishes_id, '-', -1) AS UNSIGNED)) as max_seq")
                ->value('max_seq');

            $next = $last ? $last + 1 : 1;
            $seq  = str_pad($next, 3, '0', STR_PAD_LEFT);

            $data['wishes_id'] = 'WISH-' . $data['sts_id'] . '-' . $seq;
        }

        $wish = StsWish::create($data);   // <-- pakai $data, bukan $request->validated()

        return (new WishResource($wish))->response()->setStatusCode(201);
    }

    // GET /api/v1/wishes/{wish}
    public function show(StsWish $wish)
    {
        return new WishResource($wish);
    }

    // PATCH/PUT /api/v1/wishes/{wish}
    public function update(UpdateWishRequest $request, StsWish $wish)
    {
        $wish->update($request->validated());
        return new WishResource($wish);
    }

    // DELETE /api/v1/wishes/{wish}
    public function destroy(StsWish $wish)
    {
        $wish->delete();
        return response()->json(['message' => 'Deleted'], 200);
    }

    // GET /api/v1/sts/{st}/wishes  (nested per project)
    public function bySts(StsInformation $st, Request $request)
    {
        $q = $st->wishes()->orderByDesc('wishes_created');

        if ($s = $request->string('search')->toString()) {
            $q->where(function ($w) use ($s) {
                $w->where('wishes_sender', 'like', "%{$s}%")
                    ->orWhere('wishes_account', 'like', "%{$s}%")
                    ->orWhere('wishes', 'like', "%{$s}%");
            });
        }

        $p = $q->paginate($request->integer('per_page', 10))->withQueryString();
        $items = WishResource::collection($p->items())->resolve();

        return response()->json([
            'data' => [
                'items' => $items,
                'pagination' => [
                    'current_page' => $p->currentPage(),
                    'per_page'     => $p->perPage(),
                    'total'        => $p->total(),
                    'last_page'    => $p->lastPage(),
                    'has_more'     => $p->hasMorePages(),
                ],
            ],
        ]);
    }
}
