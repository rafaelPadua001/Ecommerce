<?php
namespace App\Services\CartService;

use App\Models\CartItem;
use App\Models\Cart;
use Exception;
use Illuminate\Support\Facades\Auth;

class CartItemService{
    protected $cart;
    protected $cartItem;
    
    public function __construct(CartItem $cartItem, Cart $cart){
        $this->cartItem = $cartItem;
        $this->cart = $cart;
    }
    public function addCartItem($id, $product)
    {
        
       try {
            $cart = $this->cart->where('user_id', '=', $id)->first();
          
            $cartItem = $this->cartItem->create([
               'cart_id' => $cart->id,
               'product_id' => $product->product['id'],
               'user_id' => $id,
               'quantity' => $product->quantity,
               'price' => $product->product['price'],
               'color' => $product->color,
            ]);
           
           //$store = $this->cartItemService->store($cartItem);
          
           return response()->json($cartItem);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function buy($userId){
        try{
            $item = $this->cartItem->where('cart_items.user_id', $userId)
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
           
            return $item;
        }
        catch(Exception $e){
            return response()->json($e);
        }
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
    public function destroy($id)
    {
        
        try{
            $cartItem = $this->cartItem->findOrFail($id)->delete();
           
            return true;
          
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
}