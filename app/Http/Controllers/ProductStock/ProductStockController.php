<?php

namespace App\Http\Controllers\ProductStock;

use App\Http\Controllers\Controller;
use App\Models\ProductStock;
use App\Models\Product;
use Illuminate\Http\Request;
use Exception;

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
                $product->update(['stock_quantity' => $request->stock_quantity]);
                return response()->json($stock);
            }
            return response()->json($stock);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
}
