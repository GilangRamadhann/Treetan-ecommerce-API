<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'app'     => config('app.name'),
        'message' => 'Treetan E-Commerce API is running',
    ]);
});
