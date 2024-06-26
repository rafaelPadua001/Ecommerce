<?php

namespace App\Http\Controllers\Likes;

use App\Http\Controllers\Controller;
use App\Models\LikedProduct;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikedProductController extends Controller
{
    //
    public function index(){
        try{
            $customer = $this->getAuthenticated();
            $likes = LikedProduct::where('user_id', $customer->id)->get();
            return response()->json($likes);
        }
        catch(Exception $e){
           return response()->json($e);
        }
    }
    public function store($product, $customer){
        try{
            $liked = LikedProduct::where('product_id', $product->id)->first();
            $liked = LikedProduct::create([
                'product_id' => $product->id,
                'user_id' => $customer->id,
                'likes' => 1 ?: $liked->likes += 1,
            ]);
            return response()->json($liked);          
        }catch(Exception $e){
            return response()->json($e);
        }
       
    }
    public function destroy($product, $customer){
        try{
            $liked = LikedProduct::where('product_id', $product->id)->delete();
            return response()->json($liked);          
        }catch(Exception $e){
            return response()->json($e);
        }
       
    }
    public function getAuthenticated(){
        $customer = Auth::guard('customer')->user();
        return $customer;
    }
}
