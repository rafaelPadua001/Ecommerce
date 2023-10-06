<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Cart\CartItemController;
use App\Models\Cart;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    //
    protected $cart;

    public function __construct(Cart $cart){
        $this->cart = $cart;
    }
    public function getCarts(){
        try{
            $customer = Auth::guard('customer')->user();
            
            $carts = Cart::where('carts.user_id', $customer->id)
            ->join('cart_items', 'cart_items.cart_id', '=', 'carts.id')
            ->join('products', 'cart_items.product_id', '=', 'products.id')
            ->select(
                'cart_items.*',
                'products.name',
                'products.price',
                'products.images',
            )
            ->get();
            return response()->json($carts);
        }
        catch(Exception $e){
            return response()->json($e);
        }
        
    }
    public function addItem(Request $request){
        try{
            $customer = Auth::guard('customer')->user();
            $cart = Cart::where('carts.user_id', $customer->id)->first();

            if(!$cart){
                $item = Cart::create([
                    'user_id' => $customer->id,
                 ]);
            }
           
           
            $cart_item = $this->getCartItem($customer->id, $request);

            return response()->json($item);
        }
        catch(Exception $e){
            return response()->json($e);
        }

        
    }
    public function getCartItem($id, $product){
       
        $cartItem = new CartItemController();
        return $cartItem->addCartItem($id, $product);
    }
}
