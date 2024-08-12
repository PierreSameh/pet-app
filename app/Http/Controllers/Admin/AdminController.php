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

    public function saveStore(Request $request){
        try {
        $validator = Validator::make($request->all(), [
            'name'=> 'required|string|max:255|unique:stores,name',
            'picture'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('Invalid', $validator->errors()->first());
        }

        $store = new Store();
        $store->name = $request->name;

        if ($request->picture) {
            $imagePath = $request->file('picture')->store('/storage/store', 'public');
            $store->picture = $imagePath;
        }

        $store->save();
        return redirect()->back()->with('success', 'Store Saved');

        } catch (\Exception $e) {
            return redirect()->back()->with('Invalid', $e->getMessage());
        }
    }
}

