<?php

// routes/api.php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StsInformationController;

Route::prefix('v1')->group(function () {
    Route::apiResource('sts', StsInformationController::class)
        ->parameters(['sts' => 'st']); // {st} → StsInformation
});
