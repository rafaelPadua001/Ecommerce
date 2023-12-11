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
                'color' => $product->color,
            ]);
             
        
            return response()->json($cartItem);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function buy(){
        $customer = Auth::guard('customer')->user();
        $item = CartItem::where('cart_items.user_id', $customer->id)
        ->join('products', 'products.id', '=', 'cart_items.product_id')
        ->select(
            'cart_items.*',
            'products.name',
            'products.images',
            'products.height',
            'products.width',
            'products.length',
            'products.weight'
        )
        ->latest()
        ->first();
        return response()->json($item);
        
    }
    public function checkout($id){
        
        try{
            $cartItem = CartItem::where('cart_items.id', $id)
                ->join('products', 'cart_items.product_id', '=', 'products.id')
                //->join('addresses', 'cart_items.user_id', '=',  'addresses.user_id')
                ->select('cart_items.*', 'products.*', /*'addresses.*'*/)
                ->first();
            
            return response()->json($cartItem);
        }
        catch(Exception $e){
            return response()->json($e);
        };
    }
    public function destroy($id){
    
        try{
            $cartItem = CartItem::findOrFail($id)->delete();
           
            return true;
          
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
}
