<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Middleware\GuestAdminMiddleware;
use App\Http\Controllers\User\StoreController;
use App\Http\Controllers\User\ClinicController;

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

        //Clinics
        Route::get('/clinic/add-clinic', [AdminController::class,'addClinic'])->name('admin.add.clinic');
        Route::post('/clinic/add-clinic', [ClinicController::class, 'addClinic'])->name('admin.save.clinic');
        Route::get('/clinic/all', [AdminController::class,'getAllClinics'])->name('admin.get.clinics');
        Route::get('/clinic/edit/{clinic}', [AdminController::class,'editClinic'])->name('admin.edit.clinic');
        Route::post('/clinic/{clinic}/edit-clinic', [ClinicController::class, 'editClinic'])->name('admin.update.clinic');
        Route::post('/clinic/{clinic}/delete-clinic', [ClinicController::class,'deleteClinic'])->name('admin.delete.clinic');



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
