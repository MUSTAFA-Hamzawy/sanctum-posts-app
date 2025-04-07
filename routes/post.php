<?php
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')
    ->prefix('post')
    ->controller(PostController::class)
    ->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('{post}', 'show')->whereNumber('post');
    Route::patch('{post}', 'update')->whereNumber('post');
    Route::delete('{post}', 'destroy')->whereNumber('post');
});
