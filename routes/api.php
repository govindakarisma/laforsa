<?php

use App\Http\Controllers\AuthUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', AuthUserController::class);
});
