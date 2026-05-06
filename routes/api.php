<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CarImageController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ================================================================
// AUTH — public
// ================================================================
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
    Route::post('logout',   [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

// ================================================================
// PUBLIC — sans authentification
// ================================================================
Route::get('cars',       [CarController::class,      'index']);
Route::get('categories', [CategorieController::class, 'index']);
Route::get('settings', [SettingController::class, 'index']);

// ================================================================
// PROTÉGÉES — auth:sanctum
// ================================================================
Route::middleware('auth:sanctum')->group(function () {
    

Route::put('settings', [SettingController::class, 'update']);
    // ── Utilisateur connecté ──────────────────────────────────
    Route::get('user', [AuthController::class, 'getUser']);

    // ── Voitures ──────────────────────────────────────────────
    Route::apiResource('cars', CarController::class)->except(['index']);

    // ── Images voitures ───────────────────────────────────────
    Route::prefix('cars')->group(function () {
        Route::get('{car}/images',              [CarImageController::class, 'index']);
        Route::put('car-images/{carImage}/primary', [CarImageController::class, 'setPrimary']);
        Route::delete('car-images/{carImage}',      [CarImageController::class, 'destroy']);
    });

    Route::post('upload', [SettingController::class, 'store']);

    // ── Catégories ────────────────────────────────────────────
    Route::apiResource('categories', CategorieController::class)->except(['index']);

    // ── Commandes ─────────────────────────────────────────────
    Route::prefix('commandes')->group(function () {
        Route::get('/',                    [CommandeController::class, 'index']);
        Route::get('{commande}',           [CommandeController::class, 'show']);
        Route::put('{commande}/status',    [CommandeController::class, 'updateStatus']);
        Route::delete('{commande}',        [CommandeController::class, 'destroy']);
    });

    // ── Utilisateurs ──────────────────────────────────────────
    Route::prefix('users')->group(function () {
        Route::get('/',              [UserController::class, 'index']);
        Route::get('{user}',         [UserController::class, 'show']);
        Route::put('{user}/role',    [UserController::class, 'updateRole']);
        Route::delete('{user}',      [UserController::class, 'destroy']);
    });

});