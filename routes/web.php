<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        "author"   => "Delynessence",
        "creator"   => "Shalman",
        "contact"  => "www.linkedin.com/in/shalmanrafli",
        "version"  => "0.0.1",
    ], 200, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
});
