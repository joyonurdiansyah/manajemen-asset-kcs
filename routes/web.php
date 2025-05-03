<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AssetStatusController;
use App\Http\Controllers\WarehouseSiteController;
use App\Http\Controllers\DivisionUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

// Redirect root URL berdasarkan status login
Route::get('/', function () {
    return redirect(auth()->check() ? route('assets.fetch') : route('login'));
});

// Dashboard hanya untuk user yang login & terverifikasi
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated area
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Home
    Route::get('/home', function () {
        return view('home'); 
    })->name('home');

    // Manage Asset
    Route::get('/asset-status', [AssetStatusController::class, 'assetHome'])->name('assets.fetch');
    Route::get('/assets/data', [AssetStatusController::class, 'assetData']);
    Route::get('/assets/export', [AssetStatusController::class, 'exportExcelAsset'])->name('assets.export');
    Route::post('/assets/store', [AssetStatusController::class, 'storeAsset'])->name('assets.store');
    Route::get('/assets/edit/{id}', [AssetStatusController::class, 'editAsset'])->name('assets.edit');
    Route::put('/assets/update/{id}', [AssetStatusController::class, 'updateAsset'])->name('assets.update');
    Route::delete('/assets/destroy/{id}', [AssetStatusController::class, 'destroyAsset'])->name('assets.destroy');

    // Manage Site
    Route::get('/master-site', [WarehouseSiteController::class, 'siteHome'])->name('site.home');
    Route::get('/master-site/data', [WarehouseSiteController::class, 'getSiteData'])->name('site.data');
    Route::get('/master-site/export', [WarehouseSiteController::class, 'exportExcel'])->name('site.export');
    Route::post('/master-site/store', [WarehouseSiteController::class, 'store'])->name('site.store');
    Route::get('/master-site/edit/{id}', [WarehouseSiteController::class, 'edit'])->name('site.edit');
    Route::put('/master-site/update/{id}', [WarehouseSiteController::class, 'update'])->name('site.update');
    Route::delete('/master-site/destroy/{id}', [WarehouseSiteController::class, 'destroy'])->name('site.destroy');

    // User Divisions
    Route::get('/user-division', [DivisionUserController::class, 'userDivisionHome'])->name('user.division.home');
    Route::get('/user-division/get', [DivisionUserController::class, 'getAllDivisionsWithUsers'])->name('user.division.get');

    // Category
    Route::get('/category-data', [CategoryController::class, 'categoryHome'])->name('category.home');
    Route::get('/category-data/get', [CategoryController::class, 'CategoryGet'])->name('category.get');

    // permission
    Route::get('/permissions', [PermissionController::class, 'permissionHome'])->name('role.permissions.index');
    Route::post('/permissions/update', [PermissionController::class, 'update'])->name('permissions.update');
});

// Guest area (hanya bisa diakses jika belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

require __DIR__.'/auth.php';
