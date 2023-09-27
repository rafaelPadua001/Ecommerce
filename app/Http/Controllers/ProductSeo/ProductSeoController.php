<?php

namespace App\Http\Controllers\ProductSeo;

use App\Http\Controllers\Controller;
use App\Models\ProductSeo;
use Exception;
use Illuminate\Http\Request;


class ProductSeoController extends Controller
{
    //
    private $product_seo;
    public function __create(ProductSeo $product_seo){
        $this->product_seo = $product_seo;
    }
    public function index(){
        $seo = ProductSeo::all();
        return response()->json($seo);
    }
    public function store( 
        $name, 
        $meta_name,
        $meta_key, 
        $meta_description, 
        $slug,
        $product_id, 
        $user_id
    ){
    
        try{
            
            $product_seo = ProductSeo::create([
                'name' => $name,
                'meta_name' => $meta_name,
                'meta_keyword' => $meta_key,
                'meta_description' => $meta_description,
                'slug' => $slug,
                'product_id' => $product_id,
                'user_id' => $user_id
            ]);
            return response()->json($product_seo);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function update(Request $request, $id){
        $product = ProductSeo::where('id', $id)->first();
        
        try{
            $product->update($request->all());
           
            return response()->json($product);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
}
