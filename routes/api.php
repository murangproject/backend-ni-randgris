<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum'], 'excluded_middleware' => 'throttle:api'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/role', [AuthController::class, 'role']);

    Route::get('/check-auth', function () {
        return response()->json(['user' => auth()->user()]);
    });

    // User
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}/reset', [UserController::class, 'resetPassword']);
    Route::post('/users', [UserController::class, 'store']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::patch('/users/{id}', [UserController::class, 'restore']);
});

