<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriberController;

Route::middleware('api')->group(function () {
    Route::apiResource('subscribers', SubscriberController::class);
});
