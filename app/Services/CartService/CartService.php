<?php

namespace App\Services\CartService;

use App\Models\Cart;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Cart\CartItemController;
use App\Http\Controllers\ProductStock\ProductStockController;


class CartService {


   // public function __construct(protected Cart $carts){}

    public function getCarts() {
       
        try{
            $customer = Auth::guard('customer')->user();
            if(!$customer){
                return false;
            } 
            $carts = Cart::where('carts.user_id', $customer->id)
            ->join('cart_items', 'cart_items.cart_id', '=', 'carts.id')
            ->join('products', 'cart_items.product_id', '=', 'products.id')
            ->select(
                'carts.id',
                'cart_items.*',
                'products.name',
                'products.price',
                'products.images',
            )
            ->get();
            return $carts;
        }
        catch(Exception $e){
            return response()->json($e);
        }
        
        return response()->json($carts);
    }
    public function addItem($request){
        try{
            $customer = Auth::guard('customer')->user();
            if(!$customer){
                return false;
            }
            
            $cart = Cart::where('carts.user_id', $customer->id);
           
            if(!$cart){
                $item = Cart::updateOrcreate([
                    'user_id' => $customer->id,
                 ]);
            }
           
            $cart_item = $this->newItem($customer->id, $request);
            
            $stock_quantity = $this->reduceItemStock($request->quantity, $request->color, $cart_item);
           
            return $cart_item;
        }
        catch(Exception $e){
            return response()->json($e);
        }

        
    }
    public function newItem($id, $product){
       
        $cartItem = new CartItemController();
        return $cartItem->addCartItem($id, $product);
    }
    public function reduceItemStock($quantity, $cart){
        $product = new ProductStockController();
        return $product->reduceQuantity($quantity, $cart);
    }

    
}