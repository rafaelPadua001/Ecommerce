<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    //
    public function getCarts($id){
        try{
            $carts = Cart::where('user_id', $id)->get();
            return response()->json($carts);
        }
        catch(Exception $e){
            return response()->json($e);
        }
        
    }
}
