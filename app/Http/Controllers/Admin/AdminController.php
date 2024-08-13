<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HandleTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Hash;
use App\Models\Store;

class AdminController extends Controller
{
    use HandleTrait;

    public function loginPage () {
        return view("admin.auth");
    }

    public function login(Request $request) {
    
        $createAdmin = Admin::all()->count() > 0 ? '' : Admin::create(['username' => 'Admin', 'email' => 'admin@gmail.com', 'password' => Hash::make('admin123'), "role" => "Master"]);


        $credentials = ['email' => $request->input('email'), 'password' => $request->input('password')];

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect('admin/dashboard');
        }

        return redirect()->back()->with('Invalid', 'Invalid Email Or Password');
    }

    public function index() {
        return view("admin/index");
    }

    public function addStore(){
        return view("admin.store.add");
    }

    public function getAllStores(){
        $stores = Store::all();
        return view("admin.store.stores", compact("stores"));
    }

    public function editStore($storeId) {
        $store = Store::find($storeId);
        if ($store) {
            return view("admin.store.edit", compact("store"));
        }
        return redirect()->back()->with("red","Not Found");
    }
}

