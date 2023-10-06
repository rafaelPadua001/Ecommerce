<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{
   
    public function addCartItem($id, $product)
    {
        $customer = Customer::findOrFail($id);
        $cart = Cart::where('user_id', '=', $customer->id)->first();
   
        try {
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->product['id'],
                'user_id' => $id,
                'quantity' => $product->quantity,
                'price' => $product->product['price'],
            ]);
             
          
            return response()->json($cartItem);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
}
