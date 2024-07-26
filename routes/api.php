<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\PetController;




Route::group(['namespace' => 'User'], function () {
    // AuthController
Route::post('/user/register', [AuthController::class, 'register']);
Route::post('/user/login', [AuthController::class, 'login']);
Route::get('/user/user', [AuthController::class,'getUser'])->middleware('auth:sanctum');
Route::get('/user/ask-email-verfication-code', [AuthController::class, "askEmailCode"])->middleware('auth:sanctum');
Route::post('/user/verify-email', [AuthController::class, "verifyEmail"])->middleware('auth:sanctum');
Route::post('/user/change-password', [AuthController::class, "changePassword"])->middleware('auth:sanctum');
Route::post('/user/forgot-password', [AuthController::class, "forgetPassword"]);
Route::post('/user/forgot-password-check-code', [AuthController::class, "forgetPasswordCheckCode"]);
    // PetContoller
Route::get('/user/pet', [PetController::class,'getPet'])->middleware('auth:sanctum');
Route::post('/user/add-pet', [PetController::class,'addPet'])->middleware('auth:sanctum');
});