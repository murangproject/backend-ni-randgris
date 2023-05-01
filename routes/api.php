<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::put('/request-password-reset', [AuthController::class, 'requestPasswordReset']);

Route::group(['middleware' => ['auth:sanctum'], 'excluded_middleware' => 'throttle:api'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/role', [AuthController::class, 'role']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::put('/update-password', [AuthController::class, 'updatePassword']);
    Route::put('/update-profile', [AuthController::class, 'updateProfile']);

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

    // Schedules
    Route::get('/schedules', [ScheduleController::class, 'index']);
    Route::post('/schedules', [ScheduleController::class, 'store']);
    Route::put('/schedules/{id}', [ScheduleController::class, 'update']);
    Route::put('/schedules/{id}/status', [ScheduleController::class, 'updateStatus']);
    Route::get('/schedules/user/{id}', [ScheduleController::class, 'getScheduleByUserId']);
    Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy']);

    // Section
    Route::get('/sections', [SectionController::class, 'index']);
    Route::post('/sections', [SectionController::class, 'store']);
    Route::put('/sections/{id}', [SectionController::class, 'update']);
    Route::delete('/sections/{id}', [SectionController::class, 'destroy']);

    // Subjects
    Route::get('/subjects', [SubjectController::class, 'index']);
    Route::post('/subjects', [SubjectController::class, 'store']);
    Route::put('/subjects/{id}', [SubjectController::class, 'update']);
    Route::delete('/subjects/{id}', [SubjectController::class, 'destroy']);

    // Room
    Route::get('/rooms', [RoomController::class, 'index']);
    Route::post('/rooms', [RoomController::class, 'store']);
    Route::put('/rooms/{id}', [RoomController::class, 'update']);
    Route::delete('/rooms/{id}', [RoomController::class, 'destroy']);
    Route::post('/rooms/{id}/borrow', [RoomController::class, 'borrowRoom']);
    Route::post('/rooms/{id}/return', [RoomController::class, 'returnRoom']);

    // Activity Logs
    Route::get('/activities', [ActivityController::class, 'index']);
});

