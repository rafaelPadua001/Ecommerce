<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
    protected $productImages;
    protected $productVideos;
    protected $productSeo;
    protected $productStock;

    public function __construct(
        ProductService $productService,
        ProductImagesController $productImages,
        ProductVideoController $productVideos,
        ProductSeoController $productSeo,
        ProductStockController $productStock,
    ) {
        $this->productService = $productService;
        $this->productImages = $productImages;
        $this->productVideos = $productVideos;
        $this->productSeo = $productSeo;
        $this->productStock = $productStock;
    }
    public function index()
    {
        $products = $this->productService->getAll();

        foreach ($products as &$product) { // Usando & para referência ao objeto original
            $product['images'] = json_decode($product['images']);
            $product['colors'] = json_decode($product['colors']);
        }
        return response()->json($products);
    }
    public function show()
    {
        $products = Product::orderBy('id', 'desc')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('subcategories', 'categories.id', '=', 'subcategories.category_id')
            ->leftJoin('coupons', 'products.discount_id', '=', 'coupons.id')
            ->join('product_stocks', 'products.id', '=', 'product_stocks.product_id')
            ->select(
                'products.*',
                'categories.id as category_id',
                'subcategories.name as subcategoriy_id',
                'coupons.id as discount_id',
                'coupons.discount_percentage as discount_percentage',
                'product_stocks.color_qty as color_quantity',
                'product_stocks.size_qty as size_quantity'
            )
            ->get();


        return response()->json($products);
    }
    public function search(Request $request)
    {
        dd($request);
    }
    public function getProduct($id)
    {
        try {
            $product = $this->productService->getProduct($id);
            return response()->json($product);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
    public function getCategory($id)
    {
        try {
            $product = $this->productService->getPerSubcategory($id);
            return response()->json($product);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function getSubcategory($id){
        try{
            $product = $this->productService->getSubcategory($id);
            return response()->json($product);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function like($id)
    {
        try {
            $product = Product::findOrFail($id);
            $customer = $this->getAuthenticated();
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
    public function getDislikedController($product, $customer)
    {
        $like = new LikedProductController();
        return $like->destroy($product, $customer);
    }
    public function dislike($id)
    {
        $product = Product::findOrFail($id);
        $customer = $this->getAuthenticated();
        $dislikedProduct = $this->getDisLikedController($product, $customer);

        return response()->json($dislikedProduct);
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
            'lauch' => $request->lauch,
            'highlight' => $request->highlights ? true : false,
            'user_id' => (int) $request->user_id,
            'discount_id' => $request->has('discount') ? (int)$request->discount : null,

        ];

        $newProduct = $this->productService->create($product);

        $image_class = $this->productImages->store($upload_file,  $newProduct->original['id'], $request->user_id);

        $video_class = $this->productVideos->store($request->video_link, $newProduct->original['id'], $request->user_id, $request->name, $request->platform);


        $seo_class = $this->productSeo->store(
            $request->name,
            $request->meta_name,
            $request->meta_key,
            $request->meta_description,
            $request->slug,
            $newProduct->original['id'],
            $request->user_id
        );

        $stock_class = $this->productStock->store(
            $request->name,
            $request->quantity,
            $request->size,
            $request->size_qty,
            $request->colors,
            $request->color_qty,
            $newProduct->original['id'],
            $request->user_id
        );

        return response()->json($newProduct);
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
            'launch' => $request->launch,
            'highlight' => $request->highlights ? true : false,
            'user_id' => (int) $request->user_id,
            'discount_id' => $request->has('discount') ? (int)$request->discount : null,
        ];

        $updateProduct = $this->productService->update($product, $id);

        $imageUpdate = $this->productImages->update($upload_file, $id, $request);

        $videoUpdate = $this->productVideos->update($request, $id);

        $updateSeo = $this->productSeo->update($request, $id);

        $updateStock = $this->productStock->update($request, $id);

        return response()->json($updateProduct);

        try {
            $product = Product::where('id', $id)->update($request->all());

            if ($request->images) {
                $this->uploadImg($request);
            }
            $upload_file = $request->images;

            $product_id = Product::where('id', $id)->first();

            $image_class = $this->getImageClass($upload_file, $id, $request->user_id);

            //return response()->json($request);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function destroy($id)
    {
        try {
            $removeProduct = $this->productService->destroy($id);

            $imageDelete = $this->productImages->destroy($id);

            $videoDelete = $this->productVideos->destroy($id);

            $seoDelete = $this->productSeo->destroy($id);

            $updateStock = $this->productStock->destroy($id);
            return response()->json($removeProduct);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function getAuthenticated(){
        $customer = Auth::guard('customer')->user();
        return $customer;
    }
}
