<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AssetStatusController;
use App\Http\Controllers\WarehouseSiteController;
use App\Http\Controllers\DivisionUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\subcategoryController;
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
    Route::get('/get-subcategories/{category_id}', [AssetStatusController::class, 'getSubcategories']);
    Route::post('/assets/import', [AssetStatusController::class, 'importExcelAsset'])->name('assets.import');
    Route::get('/assets/import-template', [AssetStatusController::class, 'downloadImportTemplate'])->name('assets.import.template');

    // Manage Site
    Route::get('/master-site', [WarehouseSiteController::class, 'siteHome'])->name('site.home');
    Route::get('/master-site/data', [WarehouseSiteController::class, 'getSiteData'])->name('site.data');
    Route::get('/master-site/export', [WarehouseSiteController::class, 'exportExcel'])->name('site.export');
    Route::post('/master-site/store', [WarehouseSiteController::class, 'store'])->name('site.store');
    Route::get('/master-site/edit/{id}', [WarehouseSiteController::class, 'edit'])->name('site.edit');
    Route::put('/master-site/update/{id}', [WarehouseSiteController::class, 'update'])->name('site.update');
    Route::delete('/master-site/destroy/{id}', [WarehouseSiteController::class, 'destroy'])->name('site.destroy');
    Route::get('/master-site/detail/{id}', [WarehouseSiteController::class, 'getAssetStatus'])->name('site.detail');

    // User Divisions
    Route::get('/user-division', [DivisionUserController::class, 'userDivisionHome'])->name('user.division.home');
    Route::get('/user-division/get', [DivisionUserController::class, 'getAllDivisionsWithUsers'])->name('user.division.get');
    Route::post('/user-division/store', [DivisionUserController::class, 'store'])->name('user.division.store');
    Route::get('/user-division/{id}/edit', [DivisionUserController::class, 'edit'])->name('user.division.edit');
    Route::put('/user-division/{id}', [DivisionUserController::class, 'update'])->name('user.division.update');
    Route::delete('/user-division/{id}', [DivisionUserController::class, 'destroy'])->name('user.division.destroy');

    // Category
    Route::get('/category-data', [CategoryController::class, 'categoryHome'])->name('category.home');
    Route::get('/category-data/get', [CategoryController::class, 'CategoryGet'])->name('category.get');
    Route::post('/category-data/store', [CategoryController::class, 'categoryStore'])->name('category.store');
    Route::get('/category-data/{id}', [CategoryController::class, 'categoryShow'])->name('category.show');
    Route::patch('/category-data/update/{id}', [CategoryController::class, 'categoryUpdate'])->name('category.update');
    Route::delete('/category-data/delete/{id}', [CategoryController::class, 'categoryDestroy'])->name('category.destroy');
    Route::get('/category-data/{id}/asset-status', [CategoryController::class, 'getCategoryAssetStatus'])->name('category.asset-status');

    // permission
    Route::get('/permissions', [PermissionController::class, 'permissionHome'])->name('role.permissions.index');
    Route::post('/permissions/update', [PermissionController::class, 'update'])->name('permissions.update');


    // sub category
    Route::get('/subcategories', [SubcategoryController::class, 'subcategoryHome'])->name('subCategory.index');
    Route::get('/subcategories/data', [SubcategoryController::class, 'getData'])->name('subcategory.data');
    Route::post('/subcategories', [SubcategoryController::class, 'store'])->name('subcategory.store');
    Route::get('/subcategories/categories/list', [SubcategoryController::class, 'getCategoryList'])->name('category.list');
    Route::get('/subcategories/export', [SubcategoryController::class, 'export'])->name('subcategory.export');
    Route::get('/subcategories/{id}', [SubcategoryController::class, 'show'])->name('subcategory.show');
    Route::get('/subcategories/{id}/edit', [SubcategoryController::class, 'edit'])->name('subcategory.edit');
    Route::put('/subcategories/{id}', [SubcategoryController::class, 'update'])->name('subcategory.update');
    Route::delete('/subcategories/{id}', [SubcategoryController::class, 'destroy'])->name('subcategory.destroy');
    Route::get('/categories/list', [SubcategoryController::class, 'getList'])->name('category.list');
});

// Guest area (hanya bisa diakses jika belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

require __DIR__.'/auth.php';
