<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Middleware\GuestAdminMiddleware;
use App\Http\Controllers\User\StoreController;

Route::prefix('admin')->group(function () {
    Route::post("login", [AdminController::class, "login"])->middleware([GuestAdminMiddleware::class])->name("admin.login.post");
    Route::get("login", [AdminController::class, "loginPage"])->middleware([GuestAdminMiddleware::class]);

    Route::middleware(['auth:admin'])->group(function () {
        Route::get("/dashboard", [AdminController::class, "index"])->name("admin.index");

        //Store
        Route::get("/store/add-store", [AdminController::class,"addStore"])->name("admin.add.store");
        Route::post('/store/add-store', [StoreController::class,'addStore'])->name('admin.save.store');
        Route::get('/store/all', [AdminController::class,'getAllStores'])->name('admin.get.stores');
        Route::get('/store/edit/{store}', [AdminController::class,'editStore'])->name('admin.edit.store');
        Route::post('/store/{store}/edit-store', [StoreController::class, 'editStore'])->name('admin.excuteedit.store');
        Route::post('/store/{store}/delete-store', [StoreController::class,'deleteStore'])->name('admin.delete.store');



    });
});


Route::get('/unauthorized', function () {
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
