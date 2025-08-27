<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserAccountController;
use App\Http\Controllers\Api\StsInformationController;
use App\Http\Controllers\Api\WishController;

// ========== AUTH (public) ==========
Route::prefix('v1/auth')->group(function () {
    Route::post('register', [AuthController::class,'register']);
    Route::post('login',    [AuthController::class,'login']);
});

// ========== PUBLIC (no token) ==========
Route::prefix('v1')->group(function () {
    // Wishes - read & create TIDAK terproteksi
    Route::get('wishes',            [WishController::class, 'index']);
    Route::get('wishes/{wish}',     [WishController::class, 'show']);
    Route::post('wishes',           [WishController::class, 'store']);
    Route::get('sts/{st}/wishes',   [WishController::class, 'bySts']);
});

// ========== PROTECTED (auth:sanctum) ==========
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // session info
    Route::get('auth/me',      [AuthController::class,'me']);
    Route::post('auth/logout', [AuthController::class,'logout']);

    // User management
    Route::apiResource('user-account', UserAccountController::class);

    // STS - semua method TERPROTEKSI
    Route::apiResource('sts', StsInformationController::class)
        ->parameters(['sts' => 'st']); // {st} -> StsInformation binding

    // Wishes - hanya update & delete TERPROTEKSI
    Route::match(['put','patch'], 'wishes/{wish}', [WishController::class, 'update']);
    Route::delete('wishes/{wish}',                 [WishController::class, 'destroy']);
});
