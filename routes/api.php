<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\PetController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\LostController;
use App\Http\Controllers\User\StoreController;
use App\Http\Controllers\User\CartController;






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

// LostController
Route::post('/lost/add-pet', [LostController::class,'addLostPet'])->middleware('auth:sanctum');
Route::get('/lost-pets', [LostController::class,'showLostPets'])->middleware('auth:sanctum');
Route::get('/lost-pets-filter', [LostController::class, 'filterLostPets'])->middleware('auth:sanctum');
Route::get('/lost-pets/{lostpet}', [LostController::class,'getLostPet'])->middleware('auth:sanctum');
Route::post('/lost-pets/{lostpet}/delete', [LostController::class,'deleteLostPet'])->middleware('auth:sanctum');
Route::post('/lost-pets/{lostpet}/update', [LostController::class,'editLostPet'])->middleware('auth:sanctum');
Route::post('/lost-pets/{lostpet}/found', [LostController::class,'isFound'])->middleware('auth:sanctum');
Route::post('/lost-pets/{lostpet}/add-image', [LostController::class,'addImage'])->middleware('auth:sanctum');
Route::post('/lost-pets/{image}/delete-image', [LostController::class,'deleteImage'])->middleware('auth:sanctum');
// FoundPets
Route::post('/found/add-pet', [LostController::class,'addFoundPet'])->middleware('auth:sanctum');
Route::get('/found-pets', [LostController::class,'showFoundPets'])->middleware('auth:sanctum');
Route::get('/found-pets-filter', [LostController::class, 'filterFoundPets'])->middleware('auth:sanctum');
Route::get('/found-pets/{foundpet}', [LostController::class,'getFoundPet'])->middleware('auth:sanctum');
Route::post('/found-pets/{foundpet}/delete', [LostController::class,'deleteFoundPet'])->middleware('auth:sanctum');
Route::post('/found-pets/{foundpet}/update', [LostController::class,'editFoundPet'])->middleware('auth:sanctum');
Route::post('/found-pets/{foundpet}/add-image', [LostController::class,'addImageF'])->middleware('auth:sanctum');
Route::post('/found-pets/{image}/delete-image', [LostController::class,'deleteImageF'])->middleware('auth:sanctum');
// StoreController
Route::post('/store/add-store', [StoreController::class,'addStore'])->middleware('auth:sanctum');
Route::post('/store/{store}/edit-store', [StoreController::class, 'editStore'])->middleware('auth:sanctum');
Route::get('/store/{store}', [StoreController::class,'getStore'])->middleware('auth:sanctum');
Route::get('/store', [StoreController::class,'allStore'])->middleware('auth:sanctum');
Route::post('/store/{store}/delete-store', [StoreController::class,'deleteStore'])->middleware('auth:sanctum');
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::post('/store/add-category', [StoreController::class, 'addCategory'])->middleware('auth:sanctum');
Route::post('/store/{category}/edit-category', [StoreController::class, 'editCategory'])->middleware('auth:sanctum');
Route::post('/store/{category}/delete-category', [StoreController::class,'deleteCategory'])->middleware('auth:sanctum');
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::post('/store/add-product', [StoreController::class,'addProduct'])->middleware('auth:sanctum');
Route::post('/store/{product}/edit-product', [StoreController::class, 'editProduct'])->middleware('auth:sanctum');
Route::get('/store/category/{category}', [StoreController::class,'getCategory'])->middleware('auth:sanctum');
Route::get('/store/product/{product}', [StoreController::class,'getProduct'])->middleware('auth:sanctum');
Route::post('/store/{product}/delete-product', [StoreController::class,'deleteProduct'])->middleware('auth:sanctum');
Route::post('/store/{product}/add-product-image', [StoreController::class,'addProductImages'])->middleware('auth:sanctum');
Route::post('/store/{image}/delete-product-image', [StoreController::class,'deleteProductImage'])->middleware('auth:sanctum');
// CartController
Route::post('/cart/add-to-cart', [CartController::class,'addCart'])->middleware('auth:sanctum');

