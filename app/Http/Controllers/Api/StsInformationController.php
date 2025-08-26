<?php

// app/Http/Controllers/Api/StsInformationController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStsRequest;
use App\Http\Requests\UpdateStsRequest;
use App\Http\Resources\StsInfoResource;
use App\Http\Resources\StsInfoCollection;
use App\Models\StsInformation;
use Illuminate\Http\Request;

class StsInformationController extends Controller
{
    // GET /api/v1/sts?search=2025&page=1&per_page=10
    public function index(Request $request)
    {
        $q = StsInformation::query()->latest('sts_created');

        if ($s = $request->string('search')->toString()) {
            $q->where(fn($x) => $x->where('sts_id', 'like', "%{$s}%")
                ->orWhere('sts_name', 'like', "%{$s}%"));
        }

        $p = $q->paginate($request->integer('per_page', 10));

        return response()->json([
            'data' => [
                'items' => StsInfoResource::collection($p->items()),
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


    // POST /api/v1/sts
    public function store(StoreStsRequest $request)
    {
        $sts = StsInformation::create($request->validated());
        return (new StsInfoResource($sts))->response()->setStatusCode(201);
    }

    // GET /api/v1/sts/{st}
    public function show(StsInformation $st)
    {
        return new StsInfoResource($st);
    }

    // PATCH /api/v1/sts/{st}
    public function update(UpdateStsRequest $request, StsInformation $st)
    {
        $st->update($request->validated());
        return new StsInfoResource($st);
    }

    // DELETE /api/v1/sts/{st}
    public function destroy(StsInformation $st)
    {
        $st->delete();
        return response()->json(['message' => 'Deleted'], 200);
    }
}
