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
use App\Services\ProductService\ProductService;
use Exception;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function index()
    {
        // $products = Product::orderBy('id', 'desc')->get();
        $products = $this->productService->getAll();

        return response()->json($products);
    }
    public function store(Request $request)
    {
        $category = Categories::where('id', $request->category_id)->first();
        $subcategory = Subcategory::where('id', $request->subcategory_id)->first();
        $upload_file = $this->uploadImg($request);

        $product = [
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
            'user_id' => (int) $request->user_id,
            'discount_id' => $request->has('discount') ? (int)$request->discount : null,
        ];
        $newProduct = $this->productService->create($product);

        return response()->json($newProduct);
    }
    public function uploadImg($request)
    {
        $randomNames = [];


        foreach ($request->images as $file) {

            if ($file) {
                $randomName = Str::random(10) . '.webp';

                $fileName = $file['src'];

                $path = Storage::putFileAs('/public/products', $fileName, $randomName);

                // Adiciona o novo nome de arquivo ao array $randomNames
                $randomNames[] = $randomName;
                // dd($randomName);
                $product = Product::where('name', $request->name)->update([
                    'images' => json_encode($randomNames, true)
                ]);
            }
        }

        return $randomNames;
    }
    public function getImageClass($upload_file, $product_id, $user_id)
    {

        $product_images = new ProductImagesController();
        return $product_images->store($upload_file, $product_id, $user_id);
    }
    public function getVideoClass($video_link, $product_id, $user_id, $product_name, $platform)
    {
        $product_video = new ProductVideoController();
        return $product_video->store($video_link, $product_id, $user_id, $product_name, $platform);
    }
    public function getSeoClass(
        $name,
        $meta_name,
        $meta_key,
        $meta_description,
        $slug,
        $product_id,
        $user_id
    ) {
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
    public function getStockClass($name, $quantity, $size, $colors, $product_id, $user_id)
    {
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
    public function show()
    {

        $products = Product::orderBy('id', 'desc')->get();

        return response()->json($products);
    }
    public function like($id)
    {
        try {
            $product = Product::findOrFail($id);
            $customer = Auth::guard('customer')->user();
            $likedProduct = $this->getLikedController($product, $customer);
            return response()->json($likedProduct);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function getLikedController($product, $customer)
    {
        $like = new LikedProductController();
        return $like->store($product, $customer);
    }
    public function update(Request $request, $id)
    {
       
        $category = Categories::where('id', $request->category_id)->first();
        $subcategory = Subcategory::where('id', $request->subcategory_id)->first();
        $upload_file = $this->uploadImg($request);

        $product = [
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
            'user_id' => (int) $request->user_id,
            'discount_id' => $request->has('discount') ? (int)$request->discount : null,
        ];
        
        $updateProduct = $this->productService->update($product, $id);
      
        return response()->json($updateProduct);
      
    }
    public function destroy($id)
    {
        try {
            $removeProduct = $this->productService->destroy($id);
            return true;
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
}
