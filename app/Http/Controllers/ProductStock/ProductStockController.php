<?php

namespace App\Http\Controllers\ProductStock;

use App\Http\Controllers\Controller;
use App\Models\ProductStock;
use App\Models\Product;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class ProductStockController extends Controller
{
    //
    public function index(){
        $stocks = ProductStock::all();
        return response()->json($stocks);
    }
    public function store($name, $quantity, $size, $colors, $product_id, $user_id){
        try{
        
            $stock = ProductStock::create([
                'name' => $name,
                'stock_quantity' => $quantity,
                'product_size' => json_encode($size),
                'product_colors' => json_encode($colors),
                'product_id' => $product_id,
                'user_id' => $user_id,
            ]);
          
            return response()->json($stock);
        }
        catch(Exception $e){
            return response()->json($e);
        }
        
    }
    public function update(Request $request, $id){
        $stock = ProductStock::where('id', $id)->first();
        try{
            $stock->update($request->all());
            if($request->stock_quantity){
                $product = Product::where('id', $stock->product_id)->first();
                $product->update($request->all());
                return response()->json($stock);
            }
            return response()->json($stock);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function reduceQuantity($quantity, $cart){
        
        try{
            $stock = ProductStock::where('product_id', $cart->original->product_id)->first();
            $product = Product::where('id', $cart->original->product_id)->first();

            if($stock->stock_quantity > $quantity){
                $reduceQuantity = $stock->stock_quantity - $quantity;
                $reduceProduct = $product->stock_quantity - $quantity;
                $stock->update(['stock_quantity' => $reduceQuantity]);
                $product->update(['stock_quantity' => $reduceProduct]);
            }
            else{
                throw new Exception('Não é possível adquirir uma quantidade maior do que a temos em estoque.');
            }
            
            return response()->json($stock);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
}
