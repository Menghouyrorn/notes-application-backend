<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [\App\Http\Controllers\Auth\AuthController::class, 'register']);
Route::post('login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [\App\Http\Controllers\Auth\AuthController::class, 'user']);
    Route::post('logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout']);

    Route::prefix('tasks')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\TaskController::class, 'index']);
        Route::get('/user', [\App\Http\Controllers\Api\TaskController::class, 'getTaskByUser']);
        Route::get('/{id}', [\App\Http\Controllers\Api\TaskController::class, 'getById']);
        Route::post('/', [\App\Http\Controllers\Api\TaskController::class, 'create']);
        Route::patch('/{id}', [\App\Http\Controllers\Api\TaskController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\TaskController::class, 'delete']);
    });

    Route::prefix('roles')->group(function () {
        Route::get('/',[\App\Http\Controllers\API\RoleController::class,'index']);
        Route::post('/',[\App\Http\Controllers\API\RoleController::class,'create']);
        Route::patch('/{id}',[\App\Http\Controllers\API\RoleController::class,'update']);
        Route::delete('/{id}',[\App\Http\Controllers\API\RoleController::class,'delete']);
    });

    Route::prefix('permissions')->group(function () {
        Route::get('/',[\App\Http\Controllers\API\PermissionController::class,'index']);
        Route::post('/',[\App\Http\Controllers\API\PermissionController::class,'create']);
        Route::patch('/{id}',[\App\Http\Controllers\API\PermissionController::class,'update']);
        Route::delete('/{id}',[\App\Http\Controllers\API\PermissionController::class,'delete']);
    });
});
