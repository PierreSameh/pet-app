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
use App\Models\User;
use App\Models\Clinic;
use App\Models\Category;
use App\Models\Product;

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
    // Store
    public function addStore(){
        return view("admin.store.add");
    }

    public function getAllStores(){
        $stores = Store::all();
        return view("admin.store.stores", compact("stores"));
    }

    public function getStore($storeId) {
        $store = Store::find($storeId);
        return view("admin.store.get", compact("store"));
    }

    public function editStore($storeId) {
        $store = Store::find($storeId);
        if ($store) {
            return view("admin.store.edit", compact("store"));
        }
        return redirect()->back()->with("red","Not Found");
    }
    //Categories
    public function categories($storeId) {
        $categories = Category::where("store_id", $storeId)->get();
        return view("admin.categories.get", compact("categories", "storeId"));
    }

    public function editCategory($categoryId) {
        $category = Category::find($categoryId);
        $store = $category->store_id;
        if ($category) {
        return view('admin.categories.edit', compact('category', 'store'));
        }
        return redirect()->back()->with("red","Not Found");
    }
    //Products
    public function getProducts($storeId) {
        $products = Product::where("store_id", $storeId)->get();
        return view("admin.products.products", compact("products", "storeId"));
    }

    public function addProduct($storeId){
        $categories = Category::where("store_id", $storeId)->get();
        return view("admin.products.add", compact("categories","storeId"));
    }

    //Clinic
    public function addClinic(){
        return view("admin.clinic.add");
    }


    public function getAllClinics(){
        $clinics = Clinic::all();
        return view("admin.clinic.clinics", compact("clinics"));
    }

    public function editClinic($clinicId) {
        $clinic = Clinic::find($clinicId);
        if ($clinic) {
            return view("admin.clinic.edit", compact("clinic"));
        }
        return redirect()->back()->with("red","Not Found");
    }
}

