<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Middleware\GuestAdminMiddleware;
use App\Http\Controllers\User\StoreController;
use App\Http\Controllers\User\ClinicController;
use App\Models\Admin;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::post("login", [AdminController::class, "login"])->middleware([GuestAdminMiddleware::class])->name("admin.login.post");
    Route::get("login", [AdminController::class, "loginPage"])->middleware([GuestAdminMiddleware::class])->name("admin.login");

    Route::middleware(['auth:admin'])->group(function () {
        Route::get("/dashboard", [AdminController::class, "index"])->name("admin.index");
        Route::post("/payment/update", [AdminController::class, "addPayment"])->name('admin.add.payment');

        //Store
        Route::get("/store/add-store", [AdminController::class,"addStore"])->name("admin.add.store");
        Route::post('/store/add-store', [StoreController::class,'addStore'])->name('admin.save.store');
        Route::get('/store/all', [AdminController::class,'getAllStores'])->name('admin.get.stores');
        Route::get('/store/edit/{store}', [AdminController::class,'editStore'])->name('admin.edit.store');
        Route::get('/store/{store}', [AdminController::class,'getStore'])->name('admin.get.store');
        Route::post('/store/{store}/edit-store', [StoreController::class, 'editStore'])->name('admin.excuteedit.store');
        Route::post('/store/{store}/delete-store', [StoreController::class,'deleteStore'])->name('admin.delete.store');
        //Categories
        Route::get('/store/{store}/categories', [AdminController::class,'categories'])->name('admin.get.categories');
        Route::post('/store/{store}/add-category', [StoreController::class, 'addCategory'])->name('admin.save.category');
        Route::get('/store/category/edit/{category}', [AdminController::class,'editCategory'])->name('admin.edit.category');
        Route::post('/store/category/{category}/edit-category', [StoreController::class, 'editCategory'])->name('admin.update.category');
        Route::post('/store/category/{category}/delete-category', [StoreController::class,'deleteCategory'])->name('admin.delete.category');
        //Products
        Route::get('/store/{store}/products', [AdminController::class, 'getProducts'])->name('admin.get.products');
        Route::get('/store/{store}/add-product', [AdminController::class, 'addProduct'])->name('admin.add.product');
        Route::get('/store/product/edit/{product}', [AdminController::class,'editProduct'])->name('admin.edit.product');
        Route::post('/store/{product}/edit-product', [StoreController::class, 'editProduct'])->name('admin.update.product');
        Route::post('/store/add-product', [StoreController::class,'addProduct'])->name('admin.save.product');
        Route::post('/store/{product}/delete-product', [StoreController::class,'deleteProduct'])->name('admin.delete.product');
        Route::post('/store/{image}/delete-product-image', [StoreController::class,'deleteProductImage'])->name('admin.delete.productimage');
        //Orders
        Route::get('/orders', [AdminController::class,'getOrders'])->name('admin.get.orders');
        Route::get('/orders/{order}', [AdminController::class,'orderDetails'])->name('admin.get.order');


        //Clinics
        Route::get('/clinic/add-clinic', [AdminController::class,'addClinic'])->name('admin.add.clinic');
        Route::post('/clinic/add-clinic', [ClinicController::class, 'addClinic'])->name('admin.save.clinic');
        Route::get('/clinic/all', [AdminController::class,'getAllClinics'])->name('admin.get.clinics');
        Route::get('/clinic/edit/{clinic}', [AdminController::class,'editClinic'])->name('admin.edit.clinic');
        Route::post('/clinic/{clinic}/edit-clinic', [ClinicController::class, 'editClinic'])->name('admin.update.clinic');
        Route::post('/clinic/{clinic}/delete-clinic', [ClinicController::class,'deleteClinic'])->name('admin.delete.clinic');
        Route::get('/clinic/books', [AdminController::class,'getAllBooks'])->name('admin.books.clinic');
        Route::get('/clinic/book/{book}', [AdminController::class,'bookDetails'])->name('admin.books.details');

        //Users
        Route::get('/users/all', [AdminController::class,'getAllUsers'])->name('admin.users.all');
        Route::get('/users/{user}', [AdminController::class,'userDetails'])->name('admin.user.details');


    });
});


Route::get('/unauthorized', function () {
    // return to_route('admin.login');
    return response()->json(
        [
            "status" => false,
            "message" => "unauthenticated",
            "errors" => ["Your are not authenticated"],
            "data" => [],
            "notes" => []
        ]
        , 401);
    });
