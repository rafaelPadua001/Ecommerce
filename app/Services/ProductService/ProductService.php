<?php

    namespace App\Services\ProductService;
    
    use App\Models\Product;

    class ProductService {
        protected $products;
        public function __constuct(Product $products){
            $this->products = $products;
        }
        public function getAll() {
            $product = Product::orderBy('id', 'desc')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('subcategories', 'categories.id', '=', 'subcategories.category_id')
            ->leftJoin('coupons', 'products.discount_id', '=', 'coupons.id')
            ->join('product_stocks', 'products.id', '=', 'product_stocks.product_id')
            ->join('product_seos', 'products.id', '=', 'product_seos.product_id')
            ->select('products.*', 
                'categories.name as category_name', 
                'subcategories.name as subcategory_name',
                'coupons.code',
                'product_stocks.color_qty as color_qty',
                'product_stocks.size_qty as size_qty',
                'product_seos.meta_name as meta_name',
                'product_seos.meta_keyword as meta_key',
                'product_seos.meta_description as meta_description'
            )
            ->get();
            return  $product;
        }
        public function getProduct($id){
            $product = Product::findOrFail($id);
            return $product;
        }
        public function create($product){
            $productCreate = Product::create($product);
         //   $response = $productCreate->toArray();
            
            return response()->json($productCreate);
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