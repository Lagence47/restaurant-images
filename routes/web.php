<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/admin', function () {
    return response()->file(public_path('admin/index.html'));
});

Route::get('/admin/{any}', function ($any) {
    $path = public_path("admin/{$any}");
    if (file_exists($path)) {
        return response()->file($path);
    }
    abort(404);
})->where('any', '.*');
