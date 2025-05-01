<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AssetStatusController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DivisionUserController;
use App\Http\Controllers\WarehouseSiteController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');

// manage asset
Route::get('/asset-status', [AssetStatusController::class, 'assetHome'])->name(name: 'assets.fetch');
Route::get('/assets/data', [AssetStatusController::class, 'assetData']);
Route::get('/assets/export', [AssetStatusController::class, 'exportExcelAsset'])->name('assets.export');
Route::post('/assets/store', [AssetStatusController::class, 'storeAsset'])->name('assets.store');
Route::get('/assets/edit/{id}', [AssetStatusController::class, 'editAsset'])->name('assets.edit');
Route::put('/assets/update/{id}', [AssetStatusController::class, 'updateAsset'])->name('assets.update');
Route::delete('/assets/destroy/{id}', [AssetStatusController::class, 'destroyAsset'])->name('assets.destroy');

// manage site
Route::get('/master-site', [WarehouseSiteController::class, 'siteHome'])->name('site.home');
Route::get('/master-site/data', [WarehouseSiteController::class, 'getSiteData'])->name('site.data');
Route::get('/master-site/export', [WarehouseSiteController::class, 'exportExcel'])->name('site.export');
Route::post('/master-site/store', [WarehouseSiteController::class, 'store'])->name('site.store');
Route::get('/master-site/edit/{id}', [WarehouseSiteController::class, 'edit'])->name('site.edit');
Route::put('/master-site/update/{id}', [WarehouseSiteController::class, 'update'])->name('site.update');
Route::delete('/master-site/destroy/{id}', [WarehouseSiteController::class, 'destroy'])->name('site.destroy');

// user divisions
Route::get('/user-division', [DivisionUserController::class, 'userDivisionHome'])->name('user.division.home');
Route::get('/user-division/get', [DivisionUserController::class, 'getAllDivisionsWithUsers'])->name('user.division.get');

// category
Route::get('/category-data', [CategoryController::class, 'categoryHome'])->name('category.home');
Route::get('/category-data/get', [CategoryController::class, 'CategoryGet'])->name('category.get');