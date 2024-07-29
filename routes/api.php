<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\PetController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\LostController;
use App\Http\Controllers\User\StoreController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\MarketController;
use App\Http\Controllers\User\ClinicController;






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
Route::get('/cart', [CartController::class,'getCart'])->middleware('auth:sanctum');
Route::post('/cart/{cart}/edit-cart', [CartController::class,'editCart'])->middleware('auth:sanctum');
Route::post('/cart/{cart}/remove-from-cart', [CartController::class,'deleteCartItem'])->middleware('auth:sanctum');
// CheckoutController
Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->middleware('auth:sanctum');
Route::get('/order/{order}', [CheckoutController::class,'getOrder'])->middleware('auth:sanctum');
Route::get('/order', [CheckoutController::class,'allOrders'])->middleware('auth:sanctum');
Route::post('/order/{order}/cancel-order', [CheckoutController::class,'cancelOrder'])->middleware('auth:sanctum');
// MarketController
Route::post('/market/add-pet', [MarketController::class,'addMarketPet'])->middleware('auth:sanctum');
Route::post('/market/{pet}/update', [MarketController::class,'editMarketPet'])->middleware('auth:sanctum');
Route::post('/market/{pet}/delete', [MarketController::class,'deleteMarketPet'])->middleware('auth:sanctum');
Route::get('/market/dogs', [MarketController::class,'getMarketDogs'])->middleware('auth:sanctum');
Route::get('/market/cats', [MarketController::class,'getMarketCats'])->middleware('auth:sanctum');
Route::get('/market/birds', [MarketController::class,'getMarketBirds'])->middleware('auth:sanctum');
Route::get('/market/turtles', [MarketController::class,'getMarketTurtles'])->middleware('auth:sanctum');
Route::get('/market/fishes', [MarketController::class,'getMarketFishes'])->middleware('auth:sanctum');
Route::get('/market/monkeys', [MarketController::class,'getMarketMonkeys'])->middleware('auth:sanctum');
Route::get('/market/males', [MarketController::class,'getMarketMales'])->middleware('auth:sanctum');
Route::get('/market/females', [MarketController::class,'getMarketFemales'])->middleware('auth:sanctum');
Route::get('/market/pets', [MarketController::class, 'filterMarketPets'])->middleware('auth:sanctum');
Route::get('/market/{pet}', [MarketController::class,'getMarketPet'])->middleware('auth:sanctum');
Route::post('/market/{pet}/add-image', [MarketController::class,'addMarketImage'])->middleware('auth:sanctum');
Route::post('/market/{image}/delete-image', [MarketController::class,'deleteMarketImage'])->middleware('auth:sanctum');
// ClinicController
Route::post('/clinic/add-clinic', [ClinicController::class, 'addClinic'])->middleware('auth:sanctum');
Route::post('/clinic/{clinic}/edit-clinic', [ClinicController::class, 'editClinic'])->middleware('auth:sanctum');
Route::get('/clinic/{clinic}', [ClinicController::class,'getClinic'])->middleware('auth:sanctum');
Route::get('/clinic', [ClinicController::class,'allClinic'])->middleware('auth:sanctum');
Route::post('/clinic/{clinic}/delete-clinic', [ClinicController::class,'deleteClinic'])->middleware('auth:sanctum');
Route::post('/clinic/{clinic}/book-visit', [ClinicController::class,'book'])->middleware('auth:sanctum');
Route::get('/book/{book}', [ClinicController::class,'getBook'])->middleware('auth:sanctum');
Route::get('/book', [ClinicController::class,'allBooks'])->middleware('auth:sanctum');
Route::post('/book/{book}/cancel-book', [ClinicController::class,'cancelBook'])->middleware('auth:sanctum');


