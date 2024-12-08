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
use App\Http\Controllers\User\ChatController;
use App\Http\Controllers\User\MessageController;
use App\Http\Controllers\User\SupportController;
use App\Http\Controllers\User\BreedController;
use App\Http\Controllers\User\AddressController;






// AuthController
Route::post('/user/register', [AuthController::class, 'register']);
Route::post('/user/register-social', [AuthController::class, 'socialRegister']);
Route::post('/user/login', [AuthController::class, 'login']);
Route::get('/user/logout', [AuthController::class, "logout"])->middleware('auth:sanctum');
Route::get('/user/user', [AuthController::class,'getUser'])->middleware('auth:sanctum');
Route::get('/user/ask-email-verfication-code', [AuthController::class, "askEmailCode"])->middleware('auth:sanctum');
Route::post('/user/verify-email', [AuthController::class, "verifyEmail"])->middleware('auth:sanctum');
Route::post('/user/change-password', [AuthController::class, "changePassword"])->middleware('auth:sanctum');
Route::get('/user/forgot-password', [AuthController::class, "sendForgetPasswordEmail"]);
Route::post('/user/forgot-password-check-code', [AuthController::class, "forgetPasswordCheckCode"]);
Route::post('/user/forgot-password-set', [AuthController::class,'forgetPassword']);
Route::post('/user/edit', [AuthController::class,"editProfile"])->middleware('auth:sanctum');
Route::post('/user/add-bank-card', [AuthController::class,"addBankCard"])->middleware('auth:sanctum');
Route::post('/user/{card}/delete-card', [AuthController::class,'deleteBankCard'])->middleware('auth:sanctum');
Route::post('/user/add-wallet', [AuthController::class,'addWallet'])->middleware('auth:sanctum');
Route::post('/user/{wallet}/delete-wallet', [AuthController::class,'deleteWallet'])->middleware('auth:sanctum');

//AddressController
Route::post('/address/add-new', [AddressController::class, 'store'])->middleware('auth:sanctum');
Route::post('/address/edit', [AddressController::class, 'update'])->middleware('auth:sanctum');
Route::post('/address/delete', [AddressController::class, 'delete'])->middleware('auth:sanctum');
Route::get('/address/all', [AddressController::class, 'getAll'])->middleware('auth:sanctum');
Route::get('/address/get-address', [AddressController::class, 'get'])->middleware('auth:sanctum');
Route::post('/address/set-default', [AddressController::class, 'setDefault'])->middleware('auth:sanctum');
Route::get('address/get-default', [AddressController::class, 'getDefault'])->middleware('auth:sanctum');

// PetContoller
Route::get('/user/{pet}', [PetController::class,'getPet'])->middleware('auth:sanctum');
Route::get('/user/all/pets', [PetController::class,'getUserPets'])->middleware('auth:sanctum');
Route::post('/user/add-pet', [PetController::class,'addPet'])->middleware('auth:sanctum');
Route::post('/user/{pet}/update', [PetController::class,'editPet'])->middleware('auth:sanctum');
Route::post('/user/{pet}/delete', [PetController::class,'deletePet'])->middleware('auth:sanctum');
Route::post('/user/mate', [PetController::class,'mate'])->middleware('auth:sanctum');
Route::post('/user/{pet}/add-image', [PetController::class,'addImage'])->middleware('auth:sanctum');
Route::post('/user/{image}/delete-image', [PetController::class,'deleteImage'])->middleware('auth:sanctum');
// HomeController
Route::get('/home/dogs', [HomeController::class,'getDogs']);
Route::get('/home/cats', [HomeController::class,'getCats']);
Route::get('/home/birds', [HomeController::class,'getBirds']);
Route::get('/home/turtles', [HomeController::class,'getTurtles']);
Route::get('/home/fishes', [HomeController::class,'getFishes']);
Route::get('/home/monkeys', [HomeController::class,'getMonkeys']);
Route::get('/home/males', [HomeController::class,'getMales']);
Route::get('/home/females', [HomeController::class,'getFemales']);
Route::get('/pets', [HomeController::class, 'filterPets']);
Route::get('/home/{pet}', [HomeController::class,'getPetDating']);

// LostController
Route::post('/lost/add-pet', [LostController::class,'addLostPet'])->middleware('auth:sanctum');
Route::get('/lost-pets', [LostController::class,'showLostPets']);
Route::get('/lost-pets-filter', [LostController::class, 'filterLostPets']);
Route::get('/lost-pets/{lostpet}', [LostController::class,'getLostPet']);
Route::post('/lost-pets/{lostpet}/delete', [LostController::class,'deleteLostPet'])->middleware('auth:sanctum');
Route::post('/lost-pets/{lostpet}/update', [LostController::class,'editLostPet'])->middleware('auth:sanctum');
Route::post('/lost-pets/{lostpet}/found', [LostController::class,'isFound'])->middleware('auth:sanctum');
Route::post('/lost-pets/{lostpet}/add-image', [LostController::class,'addImage'])->middleware('auth:sanctum');
Route::post('/lost-pets/{image}/delete-image', [LostController::class,'deleteImage'])->middleware('auth:sanctum');
Route::get('/lost-pets/my-lost-pets/list', [LostController::class,'getMyLostPets'])->middleware('auth:sanctum');
// FoundPets
Route::post('/found/add-pet', [LostController::class,'addFoundPet'])->middleware('auth:sanctum');
Route::get('/found-pets', [LostController::class,'showFoundPets']);
Route::get('/found-pets-filter', [LostController::class, 'filterFoundPets']);
Route::get('/found-pets/{foundpet}', [LostController::class,'getFoundPet']);
Route::post('/found-pets/{foundpet}/delete', [LostController::class,'deleteFoundPet'])->middleware('auth:sanctum');
Route::post('/found-pets/{foundpet}/update', [LostController::class,'editFoundPet'])->middleware('auth:sanctum');
Route::post('/found-pets/{foundpet}/add-image', [LostController::class,'addImageF'])->middleware('auth:sanctum');
Route::post('/found-pets/{image}/delete-image', [LostController::class,'deleteImageF'])->middleware('auth:sanctum');
Route::get('/found-pets/my-found-pets/list', [LostController::class,'getMyFoundPets']);

// StoreController
Route::post('/store/add-store', [StoreController::class,'addStore'])->middleware('auth:sanctum');
Route::post('/store/{store}/edit-store', [StoreController::class, 'editStore'])->middleware('auth:sanctum');
Route::get('/store/{store}', [StoreController::class,'getStore']);
Route::get('/store', [StoreController::class,'allStore']);
Route::post('/store/{store}/delete-store', [StoreController::class,'deleteStore'])->middleware('auth:sanctum');
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::post('/store/{store}/add-category', [StoreController::class, 'addCategory'])->middleware('auth:sanctum');
Route::post('/store/{category}/edit-category', [StoreController::class, 'editCategory'])->middleware('auth:sanctum');
Route::post('/store/{category}/delete-category', [StoreController::class,'deleteCategory'])->middleware('auth:sanctum');
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::post('/store/add-product', [StoreController::class,'addProduct'])->middleware('auth:sanctum');
Route::post('/store/{product}/edit-product', [StoreController::class, 'editProduct'])->middleware('auth:sanctum');
Route::get('/store/category/{category}', [StoreController::class,'getCategory']);
Route::get('/store/product/{product}', [StoreController::class,'getProduct']);
Route::get('/products/all-products', [StoreController::class,'getAllProducts']);
Route::get('/products/all-offers', [StoreController::class,'getAllOffers']);
Route::get('/products/type', [StoreController::class,'getProductByType']);
Route::post('/store/{product}/delete-product', [StoreController::class,'deleteProduct'])->middleware('auth:sanctum');
Route::post('/store/{product}/add-product-image', [StoreController::class,'addProductImages'])->middleware('auth:sanctum');
Route::post('/store/{image}/delete-product-image', [StoreController::class,'deleteProductImage'])->middleware('auth:sanctum');
// CartController
Route::post('/cart/add-to-cart', [CartController::class,'addCart'])->middleware('auth:sanctum');
Route::get('/cart', [CartController::class,'getCart'])->middleware('auth:sanctum');
Route::post('/cart/edit-cart', [CartController::class,'editCart'])->middleware('auth:sanctum');
Route::post('/cart/{cart}/remove-from-cart', [CartController::class,'deleteCartItem'])->middleware('auth:sanctum');
Route::post('/cart/delete-cart', [CartController::class, 'deleteCart'])->middleware('auth:sanctum');
// CheckoutController
Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->middleware('auth:sanctum');
Route::get('/order/{order}', [CheckoutController::class,'getOrder'])->middleware('auth:sanctum');
Route::get('/order', [CheckoutController::class,'allOrders'])->middleware('auth:sanctum');
Route::post('/order/{order}/cancel-order', [CheckoutController::class,'cancelOrder'])->middleware('auth:sanctum');
Route::post('/track/{order}/set', [CheckoutController::class, 'setTrackOrder'])->middleware('auth:sanctum');
Route::get('/order/payment/number', [CheckoutController::class, 'getPayment'])->middleware('auth:sanctum');
// MarketController
Route::post('/market/add-pet', [MarketController::class,'addMarketPet'])->middleware('auth:sanctum');
Route::post('/market/{pet}/update', [MarketController::class,'editMarketPet'])->middleware('auth:sanctum');
Route::post('/market/{pet}/delete', [MarketController::class,'deleteMarketPet'])->middleware('auth:sanctum');
Route::get('/market/dogs', [MarketController::class,'getMarketDogs']);
Route::get('/market/cats', [MarketController::class,'getMarketCats']);
Route::get('/market/birds', [MarketController::class,'getMarketBirds']);
Route::get('/market/turtles', [MarketController::class,'getMarketTurtles']);
Route::get('/market/fishes', [MarketController::class,'getMarketFishes']);
Route::get('/market/monkeys', [MarketController::class,'getMarketMonkeys']);
Route::get('/market/males', [MarketController::class,'getMarketMales']);
Route::get('/market/females', [MarketController::class,'getMarketFemales']);
Route::get('/market/pets', [MarketController::class, 'filterMarketPets']);
Route::get('/market/{pet}', [MarketController::class,'getMarketPet']);
Route::post('/market/{pet}/add-image', [MarketController::class,'addMarketImage'])->middleware('auth:sanctum');
Route::post('/market/{image}/delete-image', [MarketController::class,'deleteMarketImage'])->middleware('auth:sanctum');
// ClinicController
Route::post('/clinic/add-clinic', [ClinicController::class, 'addClinic'])->middleware('auth:sanctum');
Route::post('/clinic/{clinic}/edit-clinic', [ClinicController::class, 'editClinic'])->middleware('auth:sanctum');
Route::get('/clinic/{clinic}', [ClinicController::class,'getClinic']);
Route::get('/clinic', [ClinicController::class,'allClinic']);
Route::post('/clinic/{clinic}/delete-clinic', [ClinicController::class,'deleteClinic'])->middleware('auth:sanctum');
Route::post('/clinic/{clinic}/book-visit', [ClinicController::class,'book'])->middleware('auth:sanctum');
Route::get('/book/{book}', [ClinicController::class,'getBook'])->middleware('auth:sanctum');
Route::get('/book', [ClinicController::class,'allBooks'])->middleware('auth:sanctum');
Route::post('/book/{book}/cancel-book', [ClinicController::class,'cancelBook'])->middleware('auth:sanctum');
Route::post('/clinic/rate', [ClinicController::class, 'rate'])->middleware('auth:sanctum');
// Chat Section
Route::post('/chats', [ChatController::class, 'store'])->middleware('auth:sanctum');
Route::get('/chats', [ChatController::class, 'index'])->middleware('auth:sanctum');
Route::post('/chats/accept/{id}', [ChatController::class, 'acceptRequest'])->middleware('auth:sanctum');
Route::post('/chats/reject/{id}', [ChatController::class, 'rejectRequest'])->middleware('auth:sanctum');
Route::post('/messages', [MessageController::class, 'store'])->middleware('auth:sanctum');
Route::get('/messages/{chat}', [MessageController::class, 'index'])->middleware('auth:sanctum');
Route::get('/notifications', [ChatController::class,'getNotifications'])->middleware('auth:sanctum');

//BreedController
Route::post('/breed/add-breed', [BreedController::class, 'addBreed'])->middleware('auth:sanctum');
Route::post('/breed/{breed}/edit-breed', [BreedController::class, 'editBreed'])->middleware('auth:sanctum');
Route::get('/breed/{breed}/get-breed', [BreedController::class, 'getBreed'])->middleware('auth:sanctum');
Route::get('/breed/get-all-breed', [BreedController::class, 'getAllBreeds']);
Route::post('/breed/{breed}/delete-breed', [BreedController::class, 'deleteBreed'])->middleware('auth:sanctum');

//Support
Route::post('/support', [SupportController::class, 'message'])->middleware('auth:sanctum');

