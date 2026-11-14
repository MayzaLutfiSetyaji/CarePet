<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    //endpoint publik tanpa token
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    //endpoint yang membutuhkan bearer token sanctum
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/pets', [PetController::class, 'index']);
        Route::post('/pets', [PetController::class, 'store']);
        Route::get('/pets/{id}', [PetController::class, 'show']);
        Route::patch('/pets/{id}', [PetController::class, 'update']);
        Route::delete('/pets/{id}', [PetController::class, 'destroy']);
    });
});