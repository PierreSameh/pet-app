<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\HandleTrait;
use App\Models\Store;
use App\Models\Category;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Product;
use App\Models\ProductImage;


class CartController extends Controller
{
    use HandleTrait;

    public function addCart(Request $request) {
        $validator = Validator::make($request->all(), [
            "product_id" => ["required"],
            "quantity" => ["numeric"],
        ]);

        if ($validator->fails()) {
            return $this->handleResponse(
                false,
                "",
                [$validator->errors()->first()],
                [],
                [
                    "Increase Quantity When product in Cart"
                ]
            );
        }
        // Get Sent Product From product_id
        $product = Product::find($request->product_id);
        $quantity = $request->quantity ? $request->quantity : 1;

        if ($product) {
            
            $user = $request->user();

            $cart = Cart::firstOrCreate([
                "user_id" => $user->id,
            ]);

            // Check if product Added to cart
            $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)->first();

            if (isset($cartItem)) {
            if(($cartItem->quantity + $quantity) < $product->quantity ) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
            return $this->handleResponse(
                true,
                "Product Adding to Cart Done Successfully",
                [],
                [
                  "cartItem" => $cartItem
                ],
                [
                    "Increase Quantity When product in Cart"
                ]
            );
            // When no Enough Stock For Selected Quantity
            } else {
                return $this->handleResponse(
                    false,
                    "No Enough Stock!",
                    [],
                    ["Available Stock:$product->quantity"],
                    []
                    );
            }
            } 
             if ($quantity > $product->quantity) {
                    return $this->handleResponse(
                        false,
                        "No Enough Stock!",
                        [],
                        ["Available Stock:$product->quantity"],
                        []
                        );
                    } else {
            // If the item does not exist, create a new cart item
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $quantity,
            ]);
            return $this->handleResponse(
                true,
                "Product Adding to Cart Done Successfully",
                [],
                [
                   "cartItem" => $cartItem
                ],
                [
                    "Increase Quantity When product in Cart"
                ]
            );
            }
        } else {
            return $this->handleResponse(
                false,
                "Product Not Found",
                [],
                [],
                []
                );
        }
    }   

    public function getCart(Request $request) {

        $cart = Cart::where('user_id', $request->user()->id)->first();
        if (!isset($cart)) {
            return $this->handleResponse(
                false,
                'Empty Cart, Add Products Now!',
                [],
                [],
                []
                );
            }
        $cartItems = CartItem::where('cart_id', $cart->id)->get();
        
        if (count($cartItems) > 0) {
            $totalPrice = 0;
            $products = [];
            foreach ( $cartItems as $cartItem ) {
                $product = Product::where('id', $cartItem->product_id)->with('productImages')->first();
                if ($product->offer == 0) {
                $totalPrice += $product->price * $cartItem->quantity;
                } else {
                    $totalPrice += $product->sale_amount * $cartItem->quantity;
                }
                $products[] = $product;
            }
            return $this->handleResponse(
                true,
                'Your Products In Cart',
                [],
                [
                    "cart_items" => $cartItems,
                     "total_price" => $totalPrice,
                     "products" => $products
                ],
                []
                );
            }
            return $this->handleResponse(
                false,
                'Empty Cart, Add Products Now!',
                [],
                [],
                []
                );
    }

    public function editCart(Request $request) {
        $validator = Validator::make($request->all(), [
            "product_id" => ["required"],
            "quantity" => ["numeric"],
        ]);

        if ($validator->fails()) {
            return $this->handleResponse(
                false,
                "",
                [$validator->errors()->first()],
                [],
                [
                    "Increase Quantity When product in Cart"
                ]
            );
        }
        $cart = Cart::where('user_id', $request->user()->id)->first();
        $product = Product::where("id", $request->product_id)->first();
        if ($cart && $product){
        if (isset($request->quantity)) {
            $cartItem =  CartItem::where("cart_id", $cart->id)->where('product_id', $request->product_id)->first();
            if (isset($cartItem) && $cartItem->quantity > 0 ) {
                if ($request->quantity < $product->quantity) {
                $cartItem->quantity = $request->quantity;
                $cartItem->save();

                return $this->handleResponse(
                    true,
                    'Quantity Updated',
                    [],
                    [
                       "cartItem" => $cartItem
                    ],
                    []
                    );
                } else {
                    return $this->handleResponse(
                        true,
                        'Product Stock is Running Low!',
                        [],
                        ["Available Stock:$product->quantity"],
                        []
                        );
            
                }
            }
          } 
        }
        return $this->handleResponse(
            false,
            "Cart Or Product Not Found",
            [],
            [],
            []
        );

    }


    public function deleteCartItem(Request $request, $cartID) {
        $validator = Validator::make($request->all(), [
            'product_id'=> 'required',
        ]);
        if ($validator->fails()) {
            return $this->handleResponse(
                false,
                '',
                [],
                [$validator->errors()->first()],
                []
                );
            }
        $cartItem = CartItem::where('cart_id', $cartID)->where('product_id', $request->product_id)->first();
        if (isset($cartItem)) {
            $cartItem->delete();
            return $this->handleResponse(
                true,
                'Product Removed From Cart',
                [],
                [],
                []
                );
            }
            return $this->handleResponse(
                false,
                "Couldn't Remove Product From Cart",
                [],
                [],
                []
                );
    }

    public function deleteCart(Request $request) {
        $validator = Validator::make($request->all(), [
        "cart_id" => ["required"],
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
        $cartID = $request->cart_id;
        $cartItems = CartItem::where("cart_id", $cartID)->get();
        if (count($cartItems) > 0) {
            foreach( $cartItems as $cartItem ) {
                $cartItem->delete();
            }
            return $this->handleResponse(
                true,
                "Cart Items Removed Successfully",
                [],
                [],
                []
            );
        }
        return $this->handleResponse(
            false,
            "Couldn't Delete Your Cart Items",
            [],
            [],
            []
            );
    }
}
        
        
    

