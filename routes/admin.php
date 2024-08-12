<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;

Route::get("/admin", function(){
    return view("admin.index");
})->name("admin.index");