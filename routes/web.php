<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AssetStatusController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');

// manage asset
Route::get('/asset-status', [AssetStatusController::class, 'assetHome'])->name(name: 'assets.fetch');
// get
Route::get('/assets/data', [AssetStatusController::class, 'assetData']);



