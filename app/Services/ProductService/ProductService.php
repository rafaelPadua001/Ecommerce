<?php

    namespace App\Services\ProductService;
    
    use App\Models\Product;

    class ProductService {
        protected $products;
        public function __constuct(Product $products){
            $this->products = $products;
        }
        public function getAll() {
            $product = Product::orderBy('id', 'desc')->get();
            return  $product;
        }
        public function create($product){
            $productCreate = Product::create($product);
            $response = $productCreate->toArray();
            
            return response()->json($response);
        }
        public function update($product, $id){
            $productUpdate = Product::findOrFail($id)->update($product);
            return $product;
        }
        public function destroy($id){
            $productDelete = Product::findOrFail($id)->delete();
            return true;
        }

    }