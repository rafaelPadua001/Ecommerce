<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;

use App\Http\Controllers\Cart\CartItemController;
use App\Http\Controllers\ProductStock\ProductStockController;
use App\Models\Cart;
use App\Services\CartService\CartService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{   
    protected $cartService;
    public function __construct(CartService $cartService){
        $this->cartService = $cartService;
    }
    public function index(){
        try{
            $carts = $this->cartService->getCarts();
             
            return response()->json($carts);
                
        }
        catch(Exception $e){
            return response()->json($e);
        }
        
    }
    public function addItem(Request $request){
        try{
           $cart_item = $this->cartService->addItem($request);
            
            return response()->json($cart_item);
        }
        catch(Exception $e){
            return response()->json($e);
        }

        
    }
    
    
}
