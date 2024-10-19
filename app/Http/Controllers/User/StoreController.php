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
                'description'=> 'nullable|string|max:1000',
                'picture'=> 'required|image|mimes:jpeg,png,jpg,gif,svg',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $store = new Store();
            $store->name = $request->name;
            $store->description = $request->description;

            if ($request->picture) {
                $imagePath = $request->file('picture')->store('/storage/store', 'public');
                $store->picture = $imagePath;
            }

            $store->save();
            return redirect()->back()->with('success', $store->name . ' Created Successfully');
            } catch (\Exception $e) {
                return redirect()->back()->withErrors($e->getMessage());
            }
    }

    public function editStore(Request $request, $storeID) {
        try {
            $validator = Validator::make($request->all(), [
                "name"=> ['string','max:255',Rule::unique('stores', 'name')->ignore($storeID)],
                'description'=> 'nullable|string|max:1000',
                'picture'=> 'nullable|image|mimes:jpeg,png,jpg,gif',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $store = Store::find($storeID);
            if (!$store) {
                return redirect()->back()->with('red','Not Found');
            }
            $store->name = $request->name;
            $store->description = $request->description;
            if ($request->picture) {
                $imagePath = $request->file('picture')->store('/storage/store', 'public');
                $store->picture = $imagePath;
            }
            $store->save();

            return redirect()->back()->with('success','Updated Successfully');

    
         } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function getStore($storeID) {
        // $store = Store::with('categories', 'products')->where('id', $storeID)->first();
        $store = Store::with(['categories' => function($query) {
            $query->with(['products']);
        }])->where('id', $storeID)->first();
        if (isset($store)) {
        return $this->handleResponse(
         true,
         "",
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
        $stores = Store::with(['categories' => function($query) {
            $query->with(['products' => function($q){
                $q->with(['productImages' => function($imgQuery) {
                    // Limit to only the first image
                    $imgQuery->limit(1);
                }])->get()->map(function($product) {
                    // Replace productImages with the first image
                    $product->firstImage = $product->productImages->first();
                    unset($product->productImages);
                    return $product;
                });
            }]);
        }])->paginate(20);

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
            return to_route('admin.get.stores')->with("success",$store->name . " Deleted Successfully");
            }
            return redirect()->back()->with("red","Couldn't Delete");
    }

    // Category
    public function addCategory(Request $request, $storeId) {
        try {
            $validator = Validator::make($request->all(), [
                'name'=> 'required|string|max:255|unique:categories,name',
                'image'=> 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
                'notes'=> 'nullable|string|max:1000',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $category = new Category();
            $category->store_id = $storeId;
            $category->name = $request->name;
            $category->notes = $request->notes;

            if ($request->image) {
                $imagePath = $request->file('image')->store('/storage/category', 'public');
                $category->image = $imagePath;
            }

            $category->save();
                return redirect()->back()->with('success','Category Added Successfully');
            } catch (\Exception $e) {
                return redirect()->back()->withErrors($e->getMessage());
            }
    }

    public function editCategory(Request $request, $categoryID) {
        try {
            $validator = Validator::make($request->all(), [
                'name'=> ['string','max:255',Rule::unique('categories', 'name')->ignore($categoryID)],
                'image'=> 'nullable|image|mimes:jpeg,png,jpg,gif',
                'notes'=> 'nullable|string|max:1000',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $category = Category::find($categoryID);
            if (!$category) {
                return redirect()->back()->with('red','Not Found');
            }
            $category->name = $request->name;
            if ($request->image) {
                $imagePath = $request->file('image')->store('/storage/category', 'public');
                $category->image = $imagePath;
            }
            $category->notes = $request->notes;
            $category->save();

            return redirect()->back()->with('success','Category Saved Successfully');
         } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function deleteCategory($categoryID) {
    
        $category = Category::where('id', $categoryID);
        if ($category->count() > 0) {
        $category->delete();
            return redirect()->back()->with('success','Category Deleted Successfully');
        } else {
            return redirect()->back()->with('red',"Couldn't delete Category");
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
                'images.*'=> 'required|image|mimes:jpeg,png,jpg,gif,svg',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $store = Store::where("id", $request->store_id)->first();
            $product = Product::create([
                "store_id"=> $store->id,
                "category_id"=> $request->category_id,
                "name"=> $request->name,
                "description"=> $request->description,
                "type"=> $request->type,
                "price"=> $request->price,
                "quantity"=> $request->quantity
            ]);
            $uploadedImages = [];
             if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('/storage/products', 'public');
    
                $productImage = ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $imagePath,
                ]);

                $uploadedImages[] = $productImage;
            }
            } 
            return redirect()->back()->with("success","Product Added Successfully");
            } catch (\Exception $e) {
                return redirect()->back()->withErrors($e->getMessage());
            }
    }

    public function editProduct(Request $request, $productID) {
        try {
            $validator = Validator::make($request->all(), [
                "category_id"=> ["required","numeric"],
                "name"=> ["required","string","max:255"],
                "description"=> ["nullable","string","max:1000"],
                "type"=> ["required","string","max:255"],
                "price"=> ["required","numeric"],
                "quantity"=> ["required","numeric","max:1000"],
                'images.*'=> 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
                'sale_amount'=> ['nullable','numeric'],

            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $product = Product::find($productID);
            if (!$product) {
                return redirect()->back()->with("red", "Product Not Found");
            }
            $product->category_id = $request->category_id;
            $product->name = $request->name;
            $product->description = $request->description;
            $product->type = $request->type;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $uploadedImages = [];
            if ($request->hasFile('images')) {
           foreach ($request->file('images') as $image) {
               $imagePath = $image->store('/storage/products', 'public');
   
               $productImage = ProductImage::create([
                   'product_id' => $product->id,
                   'image' => $imagePath,
               ]);
            
               $uploadedImages[] = $productImage;
            }
           }
           if ($request->sale_amount) {
            $product->offer = 1;
            $product->sale_amount = $request->sale_amount;
           } else {
            $product->offer = 0;
            $product->sale_amount = 0;
           }
            $product->save();

            return redirect()->back()->with("success","Product Updated");
    
         } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
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
        $store = Store::where("id", $product->store_id)->first();
        if (isset($product)) {
        return $this->handleResponse(
         true,
         "$product->name",
         [],
         [
            "product" => $product,
            "store" => $store
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

    public function getAllProducts(){
        $products = Product::with('productImages')->get()
        ->map(function($product) {
            // Extract only the first image
            $product->firstImage = $product->productImages->first();
            unset( $product->productImages);
            return $product;
        });
        if (count($products) > 0) {
            return $this->handleResponse(
                true,
                "",
                [],
                [
                    "products"=> $products
                ],
                []
                );
        }
        return $this->handleResponse(
            true,
            "Empty",
            [],
            [],
            []
        );
    }

    public function getAllOffers(){
        $offers = Product::where('offer', 1)->with('productImages')->get()
        ->map(function($offer) {
            // Extract only the first image
            $offer->firstImage = $offer->productImages->first();
            unset( $offer->productImages);
            return $offer;
        });
        if (count($offers) > 0) {
            return $this->handleResponse(
                true,
                '',
                [],
                [
                    'offers' => $offers
                ],
                []
                );
        }
        return $this->handleResponse(
            true,
            'There No Offers At The Moment',
            [],
            [],
            []
            );

    }


    public function deleteProduct($productID) {
        $product = Product::where("id", $productID)->first();
        if (isset($product)) {
            $product->delete();
            return redirect()->back()->with("success",$product->name . " Deleted Successfully");
        }
        return redirect()->back()->with("red", "Not Found");
    }

    // Product Images (not used)
    public function addProductImages(Request $request, $productID) {
        $validator = Validator::make($request->all(), [
            'images.*'=> 'required|image|mimes:jpeg,png,jpg,gif,svg',
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
            return redirect()->back()->with("success","Deleted Successfully");
        }
        return redirect()->back()->with("red","Couldn't Delete Image");
    }

    public function getProductByType(Request $request){
        $products = Product::where("type", $request->type)->with('productImages')->get()
        ->map(function($product) {
            // Extract only the first image
            $product->firstImage = $product->productImages->first();
            unset( $product->productImages);
            return $product;
        });
        if (count($products) > 0) {
        return $this->handleResponse(
            true,
            '',
            [],
            [
             "products" => $products
            ],
            []
        );
        }
        
        return $this->handleResponse(
            true,
            'No Search Matches',
            [],
            [],
            []
        );
    }

}
