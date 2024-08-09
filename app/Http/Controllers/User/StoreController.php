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
use Illuminate\Validation\Rule;
use App\Models\Product;
use App\Models\ProductImage;



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
                    "",
                    [$validator->errors()->first()],
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
                [
                    "store" => $store
                ],
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

    public function editStore(Request $request, $storeID) {
        try {
            $validator = Validator::make($request->all(), [
                "name"=> ['string','max:255',Rule::unique('stores', 'name')->ignore($storeID)],
                'picture'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            if ($validator->fails()) {
                return $this->handleResponse(
                    false,
                    "",
                    [$validator->errors()->first()],
                    [],
                    []
                );
            }
            $store = Store::find($storeID);
            if (!$store) {
                return $this->handleResponse(
                    false,
                    "Store Not Found",
                    [],
                    [],
                    []
                );
            }
            $store->name = $request->name;
            if ($request->image) {
                $imagePath = $request->file('picture')->store('/storage/store', 'public');
                $store->image = $imagePath;
            }
            $store->save();

            return $this->handleResponse(
                true,
                "Store Updated Successfully",
                [],
                [
                    "store" => $store
                ],
                []
            );
    
         } catch (\Exception $e) {
            return $this->handleResponse(
                false,
                "Coudln't Edit Your Store",
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
         [
            "store" => $store
         ],
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
        $stores = Store::paginate(20);
        if (count($stores) > 0) {
        return $this->handleResponse(
            true,
            "",
            [],
            [
                "stores" => $stores
            ],
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
                'name'=> 'required|string|max:255|unique:categories,name',
                'image'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'notes'=> 'nullable|string|max:1000',
            ]);
            if ($validator->fails()) {
                return $this->handleResponse(
                    false,
                    "",
                    [$validator->errors()->first()],
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
                [
                    "category" => $category
                ],
                []
            );
            } catch (\Exception $e) {
                return $this->handleResponse(
                    false,
                    "",
                    [$e->getMessage()],
                    [],
                    []
                );
            }
    }

    public function editCategory(Request $request, $categoryID) {
        try {
            $validator = Validator::make($request->all(), [
                'name'=> ['string','max:255',Rule::unique('categories', 'name')->ignore($categoryID)],
                'image'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'notes'=> 'nullable|string|max:1000',
            ]);
            if ($validator->fails()) {
                return $this->handleResponse(
                    false,
                    "",
                    [$validator->errors()->first()],
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
                [
                    "category" => $category,
                ],
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

    // Products
    public function addProduct(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                "category_id"=> ["required","numeric"],
                "name"=> ["required","string","max:255"],
                "description"=> ["nullable","string","max:1000"],
                "type"=> ["required","string","max:255"],
                "price"=> ["required","string","max:100"],
                "quantity"=> ["required","numeric","max:1000"],
            ]);
            if ($validator->fails()) {
                return $this->handleResponse(
                    false,
                    "",
                    [$validator->errors()->first()],
                    [],
                    []
                );
            }
            $store = Store::where("admin_id", $request->user()->id)->first();
            $product = Product::create([
                "store_id"=> $store->id,
                "category_id"=> $request->category_id,
                "name"=> $request->name,
                "description"=> $request->description,
                "type"=> $request->type,
                "price"=> $request->price,
                "quantity"=> $request->quantity
            ]);
            return $this->handleResponse(
                true,
                "Product Added Successfully",
                [],
                [
                    "product" => $product
                ],
                []
            );
            } catch (\Exception $e) {
                return $this->handleResponse(
                    false,
                    "Coudln't Add Your Product",
                    [$e->getMessage()],
                    [],
                    []
                );
            }
    }

    public function editProduct(Request $request, $productID) {
        try {
            $validator = Validator::make($request->all(), [
                "category_id"=> ["required","numeric"],
                "name"=> ["required","string","max:255"],
                "description"=> ["nullable","string","max:1000"],
                "type"=> ["required","string","max:255"],
                "price"=> ["required","string","max:100"],
                "quantity"=> ["required","numeric","max:1000"],
            ]);
            if ($validator->fails()) {
                return $this->handleResponse(
                    false,
                    "",
                    [$validator->errors()->first()],
                    [],
                    []
                );
            }
            $product = Product::find($productID);
            if (!$product) {
                return $this->handleResponse(
                    false,
                    "Category Not Found",
                    [],
                    [],
                    []
                );
            }
            $product->category_id = $request->category_id;
            $product->name = $request->name;
            $product->description = $request->description;
            $product->type = $request->type;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->save();

            return $this->handleResponse(
                true,
                "Product Updated Successfully",
                [],
                [
                    "product" => $product
                ],
                []
            );
    
         } catch (\Exception $e) {
            return $this->handleResponse(
                false,
                "Coudln't Edit Your Product",
                [$e->getMessage()],
                [],
                []
            );
        }
    }

    public function getCategory($categoryID) {
        $products = Product::with('productImages')->where("category_id", $categoryID)->paginate(20);
        $category = Category::where("id", $categoryID)->first();
        if (count($products) > 0) {
        return $this->handleResponse(
         true,
         "$category->name",
         [],
         [
            "products" => $products
         ],
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

    public function getProduct($productID) {
        $product = Product::with('productImages')->where("id", $productID)->first();
        if (isset($product)) {
        return $this->handleResponse(
         true,
         "$product->name",
         [],
         [
            "product" => $product,
         ],
         []
     );
    }
    return $this->handleResponse(
        false,
        "Not Found",
        [],
        [],
        []
        );
    } 

    public function deleteProduct($productID) {
        $product = Product::where("id", $productID)->first();
        if (isset($product)) {
            $product->delete();
            return $this->handleResponse(
                true,
                "$product->name Deleted Successfully",
                [],
                [],
                []
            );
        }
        return $this->handleResponse(
            false,
            "Product Not Found",
            [],
            [],
            []
            );
    }

    // Product Images
    public function addProductImages(Request $request, $productID) {
        $validator = Validator::make($request->all(), [
            'images.*'=> 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes'=> 'required|string|max:1000'
        ]);

        if ($validator->fails()) {
            return $this->handleResponse(
                false,
                "",
                [$validator->errors()->first()],
                [],
                []
            );
        }
        $productSet = Product::where("id", $productID)->first();
        if (isset($productSet)) {
        $uploadedImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('/storage/products', 'public');
    
                $productImage = ProductImage::create([
                    'product_id' => $productID,
                    'image' => $imagePath,
                ]);

                $uploadedImages[] = $productImage;
            } 
            $productImages = [ProductImage::where("product_id", $productID)->get()];
            return $this->handleResponse(
                true,
                "Image Added Successfully",
                [],
                [
                    "productImages" => $productImages
                ],
                []
            );            
         }  else {
            return $this->handleResponse(
                false,
                "Upload Images Correctly",
                ["No Images Uploaded"],
                [],
                []
            );
         }
        }
        return $this->handleResponse(
            false,
            "Upload Images Correctly",
            ["Product: " . $productSet . "is Null"],
            [],
            []
        );
         
    }

    public function deleteProductImage($imageID) {
        $image = ProductImage::where("id", $imageID)->first();
        if (isset($image)) {
            $image->delete();
            return $this->handleResponse(
                true,
                "Image Deleted Successfully",
                [],
                [],
                []
            );
        }
        return $this->handleResponse(
            false,
            "Image Not Found",
            [],
            [],
            []
            );
    }

}
