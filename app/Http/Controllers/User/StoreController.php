<?php

namespace App\Http\Controllers\User;


use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\HandleTrait;
use App\Models\Store;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;



class StoreController extends Controller
{
    use HandleTrait;
    
    // Store 
    public function addStore(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'name'=> 'required|string|max:255|unique:stores,name',
                'picture'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            if ($validator->fails()) {
                return $this->handleResponse(
                    false,
                    "Error Getting Your Lost Pet Informations",
                    [$validator->errors()],
                    [],
                    []
                );
            }

            $store = new Store();
            $store->admin_id = $request->user()->id;
            $store->name = $request->name;

            if ($request->picture) {
                $imagePath = $request->file('picture')->store('/storage/store', 'public');
                $store->picture = $imagePath;
            }

            $store->save();
            return $this->handleResponse(
                true,
                "Store Added Successfully",
                [],
                [$store],
                []
            );
            } catch (\Exception $e) {
                return $this->handleResponse(
                    false,
                    "Coudln't Add Your Store",
                    [$e->getMessage()],
                    [],
                    []
                );
            }
    }

    public function getStore($storeID) {
        $store = Store::where('id', $storeID)->first();
        
        if (isset($store)) {
        return $this->handleResponse(
         true,
         "Pet Data",
         [],
         [$store],
         []
            );
        }
        return $this->handleResponse(
            false,
            "Store Not Found",
            [],
            [],
            []
            );
    }

    public function allStore(){
        $stores = Store::get();
        if (count($stores) > 0) {
        return $this->handleResponse(
            true,
            "",
            [],
            [$stores],
            []
        );
    }
    return $this->handleResponse(
        false,
        "Empty",
        [],
        [],
        []
    );
    }

    public function deleteStore($storeID) {
        $store = Store::where("id", $storeID)->first();
        if (isset($store)) {
            $store->delete();
            return $this->handleResponse(
                true,
                "$store->name . 'Deleted Successfully'",
                [],
                [],
                []
                );
            }
            return $this->handleResponse(
                false,
                "Couldn't Delete Your Store",
                [],
                [],
                []
                );
    }

    // Category
    public function addCategory(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'name'=> 'required|string|max:255|unique:stores,name',
                'image'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'notes'=> 'nullable|string|max:1000',
            ]);
            if ($validator->fails()) {
                return $this->handleResponse(
                    false,
                    "Error Getting Your Category Informations",
                    [$validator->errors()],
                    [],
                    []
                );
            }

            $category = new Category();
            $category->name = $request->name;

            if ($request->image) {
                $imagePath = $request->file('image')->store('/storage/category', 'public');
                $category->image = $imagePath;
            }

            $category->save();
            return $this->handleResponse(
                true,
                "Category Added Successfully",
                [],
                [$category],
                []
            );
            } catch (\Exception $e) {
                return $this->handleResponse(
                    false,
                    "Coudln't Add Your Category",
                    [$e->getMessage()],
                    [],
                    []
                );
            }
    }

    public function editCategory(Request $request, $categoryID) {
        try {
            $validator = Validator::make($request->all(), [
                "name"=> 'required|string|max:255|unique:stores,name',
                'image'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'notes'=> 'nullable|string|max:1000',
            ]);
            if ($validator->fails()) {
                return $this->handleResponse(
                    false,
                    "Error Editting Your Category Informations",
                    [$validator->errors()],
                    [],
                    []
                );
            }
            $category = Category::find($categoryID);
            if (!$category) {
                return $this->handleResponse(
                    false,
                    "Category Not Found",
                    [],
                    [],
                    []
                );
            }
            $category->name = $request->name;
            if ($request->image) {
                $imagePath = $request->file('image')->store('/storage/category', 'public');
                $category->image = $imagePath;
            }
            $category->notes = $request->notes;
            $category->save();

            return $this->handleResponse(
                true,
                "Category Updated Successfully",
                [],
                [$category],
                []
            );
    
         } catch (\Exception $e) {
            return $this->handleResponse(
                false,
                "Coudln't Edit Your Category",
                [$e->getMessage()],
                [],
                []
            );
        }
    }

    public function deleteCategory($categoryID) {
    
        $category = Category::where('id', $categoryID);
        if ($category->count() > 0) {
        $category->delete();

        return $this->handleResponse(
            true,
            "Category Deleted Successfully",
            [],
            [],
            []
        );
        } else {
            return $this->handleResponse(
                false,
                "Couldn't Delete Your Category",
                [],
                [],
                []
            );
        }

    }

}
