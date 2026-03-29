<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\RegisterController;

Route::prefix('v1/roborage')->group(function () {
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('subcategories', SubcategoryController::class);
    Route::apiResource('registers', RegisterController::class);
    Route::post('test-email', [RegisterController::class, 'sendTestEmail']);
});