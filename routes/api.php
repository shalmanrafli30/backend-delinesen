<?php

// routes/api.php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StsInformationController;
use App\Http\Controllers\Api\WishController;

Route::prefix('v1')->group(function () {
    Route::apiResource('sts', StsInformationController::class)
        ->parameters(['sts' => 'st']); // {st} → StsInformation
});

Route::prefix('v1')->group(function () {
    // nested: semua wish milik satu project
    Route::get('sts/{st}/wishes', [WishController::class, 'bySts']);

    // CRUD wishes
    Route::apiResource('wishes', WishController::class)
        ->parameters(['wishes' => 'wish']);
});