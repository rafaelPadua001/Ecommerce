<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProductImages\ProductImagesController;
use App\Http\Controllers\ProductVideos\ProductVideoController;
use App\Http\Controllers\ProductSeo\ProductSeoController;
use App\Http\Controllers\ProductStock\ProductStockController;
use App\Http\Controllers\Likes\LikedProductController;
use App\Models\Product;
use App\Models\Categories;
use App\Models\Subcategory;

use Exception;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    //
    public function index()
    {
        $products = Product::orderBy('id', 'desc')->get();

        return response()->json($products);
    }
    public function store(Request $request)
    {
        $category = Categories::where('id', $request->category_id)->first();
        $subcategory = Subcategory::where('id', $request->subcategory_id)->first();

        try {
            $upload_file = $this->uploadImg($request);
            // $images = implode(',', $upload_file);
            $product = Product::create([
                'name' => $request->name,
                'category_id' =>  $category->id,
                'subcategory_id' =>  $subcategory->id,
                'description' => $request->description,
                'images' => json_encode($upload_file),
                'platform' => $request->platform,
                'video_links' => json_encode($request->video_link),
                'colors' => json_encode($request->colors),
                'size' => json_encode($request->size),
                'unity' => $request->unity,
                'manufacturer' => false,
                'price' => $request->price,
                'stock_quantity' => $request->quantity,
                'sku' => $request->sku,
                'weight' => $request->weight,
                'height' => $request->height,
                'width' => $request->width,
                'length' => $request->length,
                'availability' => $request->availability,
                'slug'   => $request->slug,
                'status'  => $request->status,
                'highlight' => $request->highlights ? true : false,
                'user_id' => (int) $request->user_id
                
            ]);
            
            $image_class = $this->getImageClass($upload_file, $product->id, $request->user_id);
            
          
            $video_class = $this->getVideoClass($request->video_link, $product->id, $request->user_id, $request->name, $request->platform);
         
           
            $seo_class = $this->getSeoClass(
                    $request->name,
                    $request->meta_name,
                    $request->meta_key, 
                    $request->meta_description,
                    $request->slug,
                    $product->id,
                    $request->user_id
            );
           
            $stock_class = $this->getStockClass(
                $request->name,
                $request->quantity,
                $request->size,
                $request->colors,
                $product->id,
                $request->user_id
            );
          
            return response()->json($product);
        } catch (Exception $e) {
            $response = $e;

            return response()->json($response);
        }

        return response()->json($product);
    }
    public function uploadImg($request)
    {
        $randomNames = [];

        foreach ($request->images as $file) {
            if ($file) {
                $randomName = Str::random(10) . '.webp';
                $fileName = $file['src'];

                $path = Storage::putFileAs('public/products', $fileName, $randomName);

                // Adiciona o novo nome de arquivo ao array $randomNames
                $randomNames[] = $randomName;
            }
        }

        return $randomNames;
    }
    public function getImageClass($upload_file, $product_id, $user_id)
    {

        $product_images = new ProductImagesController();
        return $product_images->store($upload_file, $product_id, $user_id);
    }
    public function getVideoClass($video_link, $product_id, $user_id , $product_name, $platform){
        $product_video = new ProductVideoController();
        return $product_video->store($video_link, $product_id, $user_id , $product_name, $platform);
    }
    public function getSeoClass(
        $name, 
        $meta_name,
        $meta_key, 
        $meta_description,
        $slug,
        $product_id, 
        $user_id
    ){
        $product_seo = new ProductSeoController();
        return $product_seo->store(
            $name, 
            $meta_name,
            $meta_key, 
            $meta_description, 
            $slug,
            $product_id, 
            $user_id
        );
    }
    public function getStockClass($name, $quantity, $size, $colors, $product_id, $user_id){
        $product_stock = new ProductStockController();
        return $product_stock->store(
            $name,
            $quantity,
            $size,
            $colors,
            $product_id,
            $user_id
        );
    }
    public function show(){

        $products = Product::orderBy('id', 'desc')->get();

        return response()->json($products);
    }
    public function like($id){
        try{
            $product = Product::findOrFail($id);
            $customer = Auth::guard('customer')->user();
            $likedProduct = $this->getLikedController($product, $customer);
            return response()->json($likedProduct);
        }
        catch(Exception $e){
            return response()->json($e);
        }
        
    }
    public function getLikedController($product, $customer){
        $like = new LikedProductController();
        return $like->store($product, $customer);
    }
    public function update(Request $request, $id)
    {
        try {
            $product = Product::where('id', $id)->first()->update($request->all());
            if ($request->images) {
                $this->uploadImg($request);
            }
            return response()->json($product);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function destroy($id)
    {
        try {
            $product = Product::where('id', (int) $id)->delete();
            return true;
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
}
