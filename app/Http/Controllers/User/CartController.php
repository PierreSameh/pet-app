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
            ->where('product_id', $request->product_id)
            ->first();

            if (isset($cartItem)) {
            if(($cartItem->quantity + $quantity) < $product->quantity ) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
            return $this->handleResponse(
                true,
                "Product Adding to Cart Done Successfully",
                [],
                [$cartItem],
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
                    [],
                    []
                    );
            }
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
                [$cartItem],
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
}
        
        
    

