<?php

namespace App\Services\StockService;

use App\Models\ProductStock;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class StockService {
    protected $productStock;
    public function __construct(ProductStock $productStock){
        $this->productStock = $productStock;
    }
    public function init(){
        try{
            $stocks = $this->productStock->all();
            return $stocks;
        }
        catch(Exception $e){
            return $e;
        }
    }
    public function create($name, $quantity, $size, $size_qty, $colors, $color_qty,  $product_id, $user_id){
        try{
        
            $stock = ProductStock::create([
                'name' => $name,
                'stock_quantity' => $quantity,
                'product_size' => json_encode($size),
                'size_qty' => json_encode($size_qty),
                'product_colors' => json_encode($colors),
                'color_qty' => json_encode($color_qty),
                'product_id' => $product_id,
                'user_id' => $user_id,
            ]);
          
            return $stock;
        }
        catch(Exception $e){
            return $e;
        }
    }
    public function update(Request $request, $id){
        try{
            $product = $this->productStock->where('id', $id)->first();
            
            if($product){
                $stock = $this->productStock->where('id', $id)->update([
                    'name' => $request->name,
                    'stock_quantity' => $request->stock_quantity,
                    'product_size' => json_encode($request->product_size),
                    'size_qty' => json_encode($request->size_qty),
                    'product_colors' => json_encode($request->product_colors),
                    'color_qty' => json_encode($request->color_qty),
                    'product_id' =>$product->product_id,
                    'user_id' => $request->user_id,
                ]);
            }
            else{
                
                $stock = $this->productStock->where('product_id', $id)->update([
                    'name' => $request->name,
                    'stock_quantity' => $request->quantity,
                    'product_size' => json_encode($request->product_size),
                    'size_qty' => json_encode($request->size_qty),
                    'product_colors' => json_encode($request->product_colors),
                    'color_qty' => json_encode($request->color_qty),
                    'product_id' => $id,
                    'user_id' => $request->user_id,
                ]);
            }
           return $stock;
        }
        catch(Exception $e){
            return $e;
        }
    }
    public function reduceItemStock($quantity, $cart){
       try{
           
            $productIds = $this->cartIds($cart);
            
            foreach($productIds as $productId){
                $id = $productId;
                $stock = $stock = $this->productStock->where('product_id', $id)->get();
                $product = Product::where('id',  $id)->get();
                
                foreach($stock as $stockItem){
                    $stockQuantity = $stockItem['stock_quantity'];
                    //$stockColorsQty = $stockItem['colors_qty'];
                    if($stockQuantity >= $quantity){
                        $reduceQuantity = $stockQuantity - $quantity;
                        // $reduceQuantityColors = $this->removeColorsQtys($stockColorsQty, $quantity);
                        // dd($reduceQuantityColors);
                        $stockItem->update(['stock_quantity' => $reduceQuantity]);
                        
                        $reduceProduct = $this->reduceProductQuantity($product, $quantity);
                       
                        return response()->json($stock);
                    }
                    else{
                        return response()->json(['error' => 'Não é possível adquirir uma quantidade maior do que a temos em estoque.'], 400);
                    }
                }
                
            }
        }
        catch(Exception $e){
            return $e;
        }
    }
    public function cartIds($productIds){
        
        $ids = [];
        foreach($productIds as $id){
            $productId = $id['product_id'];
            return $ids[] = [
                'product_ids' => $productId
            ];
        }
    }
    // public function removeColorsQtys($colorsQty, $quantity){
    //     dd($colorsQty, $quantity);
    //     foreach($colorsQty as $colorQty){
    //         return $colorQty -= $quantity;
    //     }
    // }
    public function reduceProductQuantity($product, $quantity){
        foreach($product as $item){
            $reduce = $item->stock_quantity - $quantity;
            $item->update(['stock_quantity' => $reduce]);
            
            return $product;
        }
    }
}
