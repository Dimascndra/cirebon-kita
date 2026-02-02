<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/* Route::get('/', function () {
    return view('welcome');
}); */

use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\CompanyController; // Public
use App\Http\Controllers\PageController;

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Static Pages
Route::get('/tentang', [PageController::class, 'about'])->name('pages.about');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'detail'])->name('news.show');
Route::get('/lowongan', [JobController::class, 'index'])->name('jobs.index');
Route::get('/lowongan/{slug}', [JobController::class, 'detail'])->name('jobs.show');
Route::get('/perusahaan', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/perusahaan/{slug}', [CompanyController::class, 'show'])->name('companies.show');

// Auth Pages (SPA)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login-web', [AuthController::class, 'webLogin'])->name('login.web'); // Hybrid Login
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

// Dashboard - Redirect based on role
Route::middleware('auth')->get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

// Job Application Routes
Route::middleware('auth')->group(function () {
    Route::post('/jobs/{job}/apply', [\App\Http\Controllers\ApplicantController::class, 'store'])->name('jobs.apply');
    Route::get('/my-applications', [\App\Http\Controllers\ApplicantController::class, 'index'])->name('applications.index');
    Route::get('/my-applications/{id}', [\App\Http\Controllers\ApplicantController::class, 'show'])->name('applications.show');
});

// Company Routes
Route::middleware(['auth', 'role:Company'])->prefix('company')->name('company.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Company\DashboardController::class, 'index'])->name('dashboard');

    // Applicants Management
    Route::get('/applicants', [\App\Http\Controllers\Company\ApplicantController::class, 'index'])->name('applicants.index');
    Route::get('/applicants/{application}', [\App\Http\Controllers\Company\ApplicantController::class, 'show'])->name('applicants.show');
    Route::patch('/applicants/{application}/status', [\App\Http\Controllers\Company\ApplicantController::class, 'updateStatus'])->name('applicants.updateStatus');
    Route::get('/applicants/{application}/cv', [\App\Http\Controllers\Company\ApplicantController::class, 'downloadCv'])->name('applicants.downloadCv');
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'role:SuperAdmin|Admin'])->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['create', 'edit', 'show']);

    // Roles
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)->except(['create', 'edit', 'show']);

    // Banner Ads
    Route::get('/ads/data', [\App\Http\Controllers\Admin\AdController::class, 'getData'])->name('ads.data');
    Route::resource('ads', \App\Http\Controllers\Admin\AdController::class)->except(['create', 'edit', 'show']);

    // News
    Route::get('/news/data', [\App\Http\Controllers\Admin\NewsController::class, 'getData'])->name('news.data');
    Route::resource('news', \App\Http\Controllers\Admin\NewsController::class);

    // Jobs
    Route::get('/jobs/data', [\App\Http\Controllers\Admin\JobController::class, 'getData'])->name('jobs.data');
    Route::resource('jobs', \App\Http\Controllers\Admin\JobController::class);
});

// Public Ad Click Tracking
Route::get('/ad/click/{id}', [\App\Http\Controllers\Admin\AdController::class, 'trackClick'])->name('ad.click');

require __DIR__ . '/auth.php';
require __DIR__ . '/dynamic-menus.php';
