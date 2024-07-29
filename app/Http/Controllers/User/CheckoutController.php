<?php

namespace App\Http\Controllers\User;

use App\SendMailTrait;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\HandleTrait;
use App\Models\Store;
use App\Models\Category;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\BankCard;
use App\Models\Wallet;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;


class CheckoutController extends Controller
{
    ///// Look At app/SendMailEXAMPLE.php before using SendMailTrait
    use HandleTrait, SendMailTrait;
    
    public function placeOrder(Request $request) {
        DB::beginTransaction();

        try {
            $user = $request->user();
            $cart = $user->cart()->latest()->first();
            
            // check if cart empty
            if (!isset($cart) || $cart->count() === 0) {
                return $this->handleResponse(
                    false,
                    "Your Cart is Empty. Add Products First",
                    [],
                    [],
                    []

                );
            }
            $cartItems = CartItem::where("cart_id", $cart->id)->get();

                $validator = Validator::make($request->all(), [
                    "status"=> "numeric|digits:1",
                    "payment_method"=> "required|string|max:255",
                    "payment_id"=> "required|numeric",
                ]);
                if ($validator->fails()) {
                    return $this->handleResponse(
                        false,
                        "Enter Your Payment Method",
                        [$validator->errors()->first()],
                        [],
                        []
                        );
                    }
                        


                $subtotal = 0;
            // get cart sub total
            if ($cart->count() > 0) {
                foreach ( $cartItems as $item ) {
                    $product = Product::where('id', $item->product_id)->first();
                    $subtotal += $product->price * $item->quantity;
                }
            }
            
            $order = new Order();
            $order->user_id = $user->id;
            $order->subtotal = $subtotal;
            // If using bank card
            if ($request->payment_method == "card") {
            $payment = BankCard::where("id", $request->payment_id)->first();
            $order->payment_method = "card";
            $order->payment_id = $payment->id;
            }

            if ($request->payment_method == "wallet") {
                $payment = Wallet::where("id", $request->payment_id)->first();
                $order->payment_method = "wallet";
                $order->payment_id = $payment->id;
            }
            $order->save();

            foreach ($cartItems as $item) {

                    OrderItem::create([
                        "order_id" => $order->id,
                        "product_id" => $item->product_id,
                        "order_price" => $item->product->price,
                        "order_quantity" => $item->quantity,
                    ]);
                
                $product = Product::where('id', $item->product_id)->first();
                if ($product) {
                    $product->quantity = (int) $product->quantity - (int) $item->quantity;
                    $product->save();
                }
                $item->delete();
            }

            if ($order) {
                $msg_content = "<h1>";
                $msg_content = " New Order by: " . $user->first_name . ' ' . $user->last_name;
                $msg_content .= "</h1>";
                $msg_content .= "<br>";
                $msg_content .= "<h3>";
                $msg_content .= "Order Details: ";
                $msg_content .= "</h3>";

                $msg_content .= "<h4>";
                $msg_content .= "Phone: ";
                $msg_content .= $user->phone;
                $msg_content .= "</h4>";


                $msg_content .= "<h4>";
                $msg_content .= "Address: ";
                $msg_content .= $user->address;
                $msg_content .= "</h4>";


                $msg_content .= "<h4>";
                $msg_content .= "SubTotal: ";
                $msg_content .= $order->subtotal;
                $msg_content .= "</h4>";


                $this->sendEmail($user->email, "New Order", $msg_content);

                $cart->delete();
                
            }

            DB::commit();

            return $this->handleResponse(
                true,
                "Order Submited Successfully!",
                [],
                [
                    $order, $user, $payment
                ],
                ["Order Status Meaning: 1 -> ordered, 2 -> shipped, 3 -> delivered"]
            );

        } catch (\Exception $e) {
            DB::rollBack();

            return $this->handleResponse(
                false,
                "Error Submiting Your Order",
                [$e->getMessage()],
                [],
                []
            );
        }
    }

        
    
}