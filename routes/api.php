<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Company\DashboardController as CompanyDashboardController;
use App\Http\Controllers\Company\ApplicantController as CompanyApplicantController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\AdController as AdminAdController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\JobController as AdminJobController;
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
    Route::get('/dashboard', [DashboardController::class, 'summary']);
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);
    Route::get('/applications', [ApplicantController::class, 'list']);
    Route::get('/applications/{id}', [ApplicantController::class, 'detail']);
});

Route::middleware(['auth:sanctum', 'role:Company'])->prefix('company')->group(function () {
    Route::get('/dashboard', [CompanyDashboardController::class, 'summary']);
    Route::get('/applicants', [CompanyApplicantController::class, 'list']);
    Route::get('/applicants/{application}', [CompanyApplicantController::class, 'detail']);
    Route::patch('/applicants/{application}/status', [CompanyApplicantController::class, 'updateStatus']);
});

Route::middleware(['auth:sanctum', 'role:SuperAdmin|Admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'summary']);
    Route::get('/users', [AdminUserController::class, 'list']);
    Route::get('/roles', [AdminRoleController::class, 'list']);
    Route::get('/ads', [AdminAdController::class, 'getData']);
    Route::get('/ads/{id}', [AdminAdController::class, 'detail']);
    Route::get('/news', [AdminNewsController::class, 'getData']);
    Route::get('/news/meta', [AdminNewsController::class, 'meta']);
    Route::get('/news/{id}', [AdminNewsController::class, 'detail']);
    Route::get('/jobs', [AdminJobController::class, 'getData']);
    Route::get('/jobs/meta', [AdminJobController::class, 'meta']);
    Route::get('/jobs/{id}', [AdminJobController::class, 'detail']);
});

Route::get('/news', [NewsController::class, 'list']);
Route::get('/news/categories', [NewsController::class, 'categories']);
Route::get('/news/{slug}', [NewsController::class, 'show']);
Route::get('/jobs', [JobController::class, 'list']);
Route::get('/jobs/filters', [JobController::class, 'filters']);
Route::get('/jobs/{slug}', [JobController::class, 'show']);
Route::get('/companies', [\App\Http\Controllers\CompanyController::class, 'list']);
Route::get('/companies/{slug}', [\App\Http\Controllers\CompanyController::class, 'detail']);
