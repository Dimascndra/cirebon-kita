<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('home')->group(function () {
    Route::get('/hero', [HomeController::class, 'hero']);
    Route::get('/news', [HomeController::class, 'news']);
    Route::get('/jobs', [HomeController::class, 'jobs']);
    Route::get('/categories', [HomeController::class, 'categories']);
    Route::get('/companies', [HomeController::class, 'companies']);
    Route::get('/banners', [HomeController::class, 'banners']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::get('/news', [NewsController::class, 'list']);
Route::get('/news/{slug}', [NewsController::class, 'show']);
Route::get('/jobs', [JobController::class, 'list']);
Route::get('/jobs/{slug}', [JobController::class, 'show']);
