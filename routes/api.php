<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;

Route::apiResource('categories', CategoryController::class);
Route::apiResource('subcategories', SubcategoryController::class);