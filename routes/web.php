<?php

use App\Http\Controllers\AssetCheckScheduleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AssetStatusController;
use App\Http\Controllers\WarehouseSiteController;
use App\Http\Controllers\DivisionUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\cekBarangController;
use App\Http\Controllers\detailProgresController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\subcategoryController;
use Illuminate\Support\Facades\Route;

// Redirect root URL berdasarkan status login
Route::get('/', function () {
    return redirect(auth()->check() ? route('assets.fetch') : route('login'));
});

// Guest area (hanya bisa diakses jika belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated area - semua route di bawah ini memerlukan login
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('home');
    })->name('dashboard');
    
    // Home
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    // home group
    Route::get('/brand-data', [HomeController::class, 'getBrandData'])->name('brand.data');
    Route::get('/location-data', [HomeController::class, 'getLocationData'])->name('location.data');
    // Rute untuk mendapatkan data aktivitas via AJAX
    Route::get('/get-activities', [HomeController::class, 'getActivities'])->name('activities.data');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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
    Route::post('/assets/export/filtered', [AssetStatusController::class, 'exportFilteredExcelAsset'])->name('assets.export.filtered');

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

    // Permissions
    Route::get('/permissions', [PermissionController::class, 'permissionHome'])->name('role.permissions.index');
    Route::post('/permissions/update', [PermissionController::class, 'update'])->name('permissions.update');
    Route::post('/permissions/assign-role', [PermissionController::class, 'assignRole'])->name('users.assign-role');
    Route::post('/permissions/update-roles', [PermissionController::class, 'updateUserRoles'])->name('users.update-roles');
    Route::post('/permissions/remove-role', [PermissionController::class, 'removeRole'])->name('users.remove-role');
    Route::get('/permissions/{user_id}/roles', [PermissionController::class, 'getUserRoles'])->name('users.get-roles');
    Route::get('/permissions/search', [PermissionController::class, 'searchUsers'])->name('users.search');

    // Sub Category
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

    // Cek Barang - pindahkan ke dalam middleware auth
    Route::get('/cekbarang', [cekBarangController::class, 'cekbarang'])->name('cekabarang.index');
    Route::post('/api/vision-analyze', [cekBarangController::class, 'analyze']);

    // penjadwalan audit
    Route::get('/jadwal', [AssetCheckScheduleController::class, 'jadwalHome'])->name('jadwal.index');
    // on proses
    Route::get('/jadwal/fetch', [AssetCheckScheduleController::class, 'fetch'])->name('jadwal.fetch');
    Route::post('/jadwal/store', [AssetCheckScheduleController::class, 'store'])->name('jadwal.store');
    Route::get('/jadwal/edit/{id}', [AssetCheckScheduleController::class, 'edit'])->name('jadwal.edit');
    Route::post('/jadwal/update/{id}', [AssetCheckScheduleController::class, 'update'])->name('jadwal.update');
    Route::post('/jadwal/update-status/{id}', [AssetCheckScheduleController::class, 'updateStatus'])->name('jadwal.update-status');
    Route::delete('/jadwal/delete/{id}', [AssetCheckScheduleController::class, 'destroy'])->name('jadwal.destroy');

    // detail audit schedule proses
    Route::get('/detail-audit-index', [detailProgresController::class, 'index'])->name('detail.audit.index');
    Route::get('/detail-audit/{id}', [AssetCheckScheduleController::class, 'show'])->name('asset-check-schedules.show');
    Route::put('/detail-audit/{id}/update-status', [AssetCheckScheduleController::class, 'updateStatus'])->name('asset-check-schedules.update-status');
    Route::get('/detail-audit/filter', [AssetCheckScheduleController::class, 'filter'])->name('asset-check-schedules.filter');

});

require __DIR__.'/auth.php';