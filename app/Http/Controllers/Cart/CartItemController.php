<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\CartService\CartItemService;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{
    protected $cartItemService;
    public function __construct(CartItemService $cartItemService){
        $this->cartItemService = $cartItemService;
    }
    public function buy(){
        try{
            $customer = Auth::guard('customer')->user();
            $userId = $customer->id;
            if(!$customer){
                throw new Exception('Customer not logged...');
                return false;
            }
            $cartItem = $this->cartItemService->buy($userId);

            return response()->json($cartItem);
        }
        catch(Exception $e){
            return response()->json($e);
        }
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
        $cart_item = $this->cartItemService->destroy($id);

        return response()->json($cart_item);
       // try{
       //     $cartItem = CartItem::findOrFail($id)->delete();
       //    
       //     return true;
       //   
       // }
       // catch(Exception $e){
       //     return response()->json($e);
       // }
    }
}
