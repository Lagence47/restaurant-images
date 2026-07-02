<?php

use App\Http\Controllers\Api\V1\ImageController as PublicImageController;
use App\Http\Controllers\Api\Admin\ImageController as AdminImageController;
use App\Http\Controllers\Api\ImageController;
use Illuminate\Support\Facades\Route;

// Routes publiques
Route::prefix('v1')->group(function () {
  Route::get('/images/{hash}', [PublicImageController::class, 'show']);
});

// Routes admin protégées
Route::prefix('admin')->middleware('admin.auth')->group(function () {
  Route::get('/images', [AdminImageController::class, 'index']);
  Route::get('/images/categories', [AdminImageController::class, 'categories']);
});
