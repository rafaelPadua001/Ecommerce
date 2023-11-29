<?php

namespace App\Http\Controllers\ProductStock;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Products\ProductController;
use App\Models\ProductStock;
use App\Models\Product;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class ProductStockController extends Controller
{
    //
    protected $products;
    public function __constuct(ProductController $products){
        $this->products = $products;
    }
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
         try{
            $product = ProductStock::where('id', $id)->first();
            
            if($product){
                $stock = ProductStock::where('id', $id)->update([
                    'name' => $request->name,
                    'stock_quantity' => $request->stock_quantity,
                    'product_size' => json_encode($request->size),
                    'product_colors' => json_encode($request->colors),
                    'product_id' =>$product->product_id,
                    'user_id' => $request->user_id,
                ]);
            }
            else{
                
                $stock = ProductStock::where('product_id', $id)->update([
                    'name' => $request->name,
                    'stock_quantity' => $request->quantity,
                    'product_size' => json_encode($request->size),
                    'product_colors' => json_encode($request->colors),
                    'product_id' => $id,
                    'user_id' => $request->user_id,
                ]);
                
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
