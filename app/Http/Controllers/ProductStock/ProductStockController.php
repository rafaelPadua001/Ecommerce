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
        $stock = ProductStock::where('product_id', $id)->update([
            'name' => $request->name,
                'stock_quantity' => $request->quantity,
                'product_size' => json_encode($request->size),
                'product_colors' => json_encode($request->colors),
                'product_id' => $id,
                'user_id' => $request->user_id,
        ]);
        
     //   try{
     //       $stock->update($request->all());
     //       if($request->quantity){
     //           $product = Product::where('product_id', $stock->product_id)->first();
     //           $product->update($request->all());
     //           return response()->json($stock);
     //       }
     //       return response()->json($stock);
     //   }
     //   catch(Exception $e){
     //       return response()->json($e);
     //   }

     return response()->json($stock);
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
