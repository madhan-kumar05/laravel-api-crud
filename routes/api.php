<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('throttle:60,1')->group(function () {
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']); // Login
    });

    Route::middleware('body.length')->group(function () {
        Route::apiResource('users', UserController::class)->only(['store']); // Regitser without login

        Route::middleware(['jwt.auth'])->group(function () {
            Route::get('me', [AuthController::class, 'me']); // Login user details
            Route::apiResource('users', UserController::class); // Users CRUD
        });
    });
});
