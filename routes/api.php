<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ImageController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
  Route::apiResource('categories', CategoryController::class);
  Route::apiResource('images', ImageController::class)->only(['index', 'store', 'show', 'destroy']);
});
