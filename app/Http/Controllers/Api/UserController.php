<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Shop;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){
        $users = User::all();

        if($users){
            return response()->json([
                "status" => "200",
                "data" => $users
            ], 200);
        } else {
            return response()->json([
                "status" => "404",
                "message" => "Users not Found!"
            ], 404);
        }
    }

    // public function store(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'user_id' => "required|string",
    //         'firstname' => "required|string|min:3|max:20",
    //         'lastname' => "required|string|min:3|max:20",
    //         'contact_number' => "required|min:11|max:11|unique:users,contact_number",
    //         'address' => "required|string",
    //         'password' => "required|confirmed|min:4",
    //     ]);

    //     if($validator->fails()){
    //         return response()->json([
    //             "message" => $validator->errors()
    //         ], 422);
    //     } else {
    //         $user = User::create([
    //             "user_id" => $request->user_id,
    //             "cart_id" => $request->cart_id,
    //             "firstname" => $request->firstname,
    //             "lastname" => $request->lastname,
    //             "contact_number" => $request->contact_number,
    //             "address" => $request->address,
    //             "password" => Hash::make($request->password),
    //             "role" => "U2FsdGVkX1+Rv7W03=",
    //             "status" => "active"
    //         ]);

    //         if($user){
    //             $cart = Cart::create([
    //                 "cart_id" => $request->cart_id
    //             ]);

    //             if($cart){
    //                 $selected = collect($request->cart);

    //                 $items = $selected->map(function($item) use ($request){
    //                     $item['cart_id'] = $request->cart_id;
    //                     $item['product_id'] = $item['id'];

    //                     return $item;
    //                 });

    //                 foreach($items as $item){
    //                     CartItem::create($item);
    //                 }

    //                 $client = new Client();
    //                 $client->post(env("API_URL") . '/signal', [
    //                     'json' => [
    //                         'action' => 'update', // Example signal
    //                     ]
    //                 ]);

    //                 $client->post(env("API_URL") . '/signal', [
    //                     'json' => [
    //                         'action' => 'updateSuper', // Example signal
    //                     ]
    //                 ]);

    //                 return response()->json([
    //                     "message" => "Created Successfully"
    //                 ], 200);
    //             } else {
    //                 return response()->json([
    //                     "message" => "Something Went Wrong"
    //                 ], 500);
    //             }

    //         } else {
    //             return response()->json([
    //                 "message" => "Something Went Wrong"
    //             ], 500);
    //         }
    //     }
    // }

    // public function show($user_id){
    //     $user = User::where("user_id", $user_id)->with("cart.cartItems", "checkout.checkoutItems")->first();

    //     if($user){
    //         $cartItems = CartItem::where("cart_id", $user->cart_id)->get();

    //         foreach ($cartItems as $cartItem) {
    //             $product = ProductVariant::where("id", $cartItem->variant_id)->first();
            
    //             if ($product) {
    //                 if ($product->stock == 0 || $product->pre_order == 1 || $product->stock < 0 || $cartItem->quantity < 0) {
    //                     // Delete the cart item if the product is out of stock
    //                     $cartItem->delete();
    //                 } elseif ($cartItem->quantity > $product->stock) {
    //                     // Update the cart item's quantity to match the product's stock
    //                     $cartItem->update([
    //                         "quantity" => $product->stock
    //                     ]);
    //                 }
    //             }
    //         }

    //         $updatedUser = User::where("user_id", $user_id)->with("cart.cartItems", "checkout.checkoutItems")->first();

    //         $orders = Order::where('user_id', $updatedUser->id)->with("items")->orderBy("created_at", "desc")->get();

    //         $notification = Notification::where("user_id", $updatedUser->id)->get();

    //         return response()->json([
    //             "status" => "200",
    //             "data" => $updatedUser,
    //             "orders" => $orders,
    //             "notifications" => $notification,
    //         ], 200); 
    //     } else {
    //         return response()->json([
    //             "status" => "404",
    //             "message" => "User Not Found!"
    //         ], 404);
    //     }
    // }

    public function changePassword(Request $request, $id){
        $user = User::where("id", $id)->first();

        if($user){
            $validator = Validator::make($request->all(), [
                "password" => "required|confirmed|min:4"
            ]);

            if($validator->fails()){
                return response()->json([
                    "status" => "422",
                    "message" => $validator->errors()
                ], 422);
            } else {
                $user->update([
                    "password" => Hash::make($request->password)
                ]);

                if($user){                    
                    return response()->json([
                        "status" => "200",
                        "message" => "Password Updated Successfully"
                    ], 200);
                } else {
                    return response()->json([
                        "status" => "500",
                        "message" => "Something Went Wrong"
                    ]);
                }
            }
        } else {
            return response()->json([
                "status" => "404",
                "message" => "User Not Found"
            ], 404);
        }
    }

    public function delete($user_id){
        $user = User::where("id", $user_id)->first();

        if($user){
            $user->delete();

            $client = new Client();
            $client->post(env("API_URL") . '/signal', [
                'json' => [
                    'action' => 'logout', // Example signal
                ]
            ]);

            return response()->json([
                "status" => "200",
                "message" => "User deleted"
            ], 200);
        } else {
            return response()->json([
                "status" => "404",
                "message" => "User not found"
            ], 404);
        }
    }

    public function update(Request $request, $id){
        $user = User::where("id", $id)->first();

        if($user){
            $validator = Validator::make($request->all(), [
                'username' => "required|string|min:3|max:20",
            ]);
            
            if($validator->fails()){
                return response()->json([
                    "message" => $validator->errors()
                ], 422);
            } else {
                $user->update([
                    "username" => $request->username,
                ]);

                if($user){        
                    return response()->json([
                        "status" => "200",
                        "message" => "Account Updated",
                        "user" => $user,
                    ], 200);
                }
            }
        } else {
            return response()->json([
                "status" => "404",
                "message" => "User not found"
            ], 404);
        }
    }
}