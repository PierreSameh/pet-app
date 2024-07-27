<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\PetController;
use App\Http\Controllers\User\HomeController;






// AuthController
Route::post('/user/register', [AuthController::class, 'register']);
Route::post('/user/login', [AuthController::class, 'login']);
Route::get('/user/user', [AuthController::class,'getUser'])->middleware('auth:sanctum');
Route::get('/user/ask-email-verfication-code', [AuthController::class, "askEmailCode"])->middleware('auth:sanctum');
Route::post('/user/verify-email', [AuthController::class, "verifyEmail"])->middleware('auth:sanctum');
Route::post('/user/change-password', [AuthController::class, "changePassword"])->middleware('auth:sanctum');
Route::post('/user/forgot-password', [AuthController::class, "forgetPassword"]);
Route::post('/user/forgot-password-check-code', [AuthController::class, "forgetPasswordCheckCode"]);
Route::post('/user/edit', [AuthController::class,"editProfile"])->middleware('auth:sanctum');
Route::post('/user/add-bank-card', [AuthController::class,"addBankCard"])->middleware('auth:sanctum');
Route::post('/user/{card}/delete-card', [AuthController::class,'deleteBankCard'])->middleware('auth:sanctum');
Route::post('/user/add-wallet', [AuthController::class,'addWallet'])->middleware('auth:sanctum');
Route::post('/user/{wallet}/delete-wallet', [AuthController::class,'deleteWallet'])->middleware('auth:sanctum');

// PetContoller
Route::get('/user/{pet}', [PetController::class,'getPet'])->middleware('auth:sanctum');
Route::post('/user/add-pet', [PetController::class,'addPet'])->middleware('auth:sanctum');
Route::post('/user/{pet}/update', [PetController::class,'editPet'])->middleware('auth:sanctum');
Route::post('/user/{pet}/delete', [PetController::class,'deletePet'])->middleware('auth:sanctum');
Route::post('/user/{pet}/add-image', [PetController::class,'addImage'])->middleware('auth:sanctum');
Route::post('/user/{image}/delete-image', [PetController::class,'deleteImage'])->middleware('auth:sanctum');
// HomeController
Route::get('/home/dogs', [HomeController::class,'getDogs'])->middleware('auth:sanctum');
Route::get('/home/cats', [HomeController::class,'getCats'])->middleware('auth:sanctum');
Route::get('/home/birds', [HomeController::class,'getBirds'])->middleware('auth:sanctum');
Route::get('/home/turtles', [HomeController::class,'getTurtles'])->middleware('auth:sanctum');
Route::get('/home/fishes', [HomeController::class,'getFishes'])->middleware('auth:sanctum');
Route::get('/home/monkeys', [HomeController::class,'getMonkeys'])->middleware('auth:sanctum');
Route::get('/home/males', [HomeController::class,'getMales'])->middleware('auth:sanctum');
Route::get('/home/females', [HomeController::class,'getFemales'])->middleware('auth:sanctum');
Route::get('/pets', [HomeController::class, 'filterPets'])->middleware('auth:sanctum');
Route::get('/home/{pet}', [HomeController::class,'getPetDating'])->middleware('auth:sanctum');