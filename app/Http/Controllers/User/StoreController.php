<?php

namespace App\Http\Controllers\User;


use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\HandleTrait;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;



class StoreController extends Controller
{
    use HandleTrait;
    
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
}
