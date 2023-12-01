<?php

namespace App\Http\Controllers\ProductVideos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductVideos;
use App\Models\Product;
use Exception;

class ProductVideoController extends Controller
{
    //
    public function index(){
        $videos = ProductVideos::join('products', 'product_videos.product_id', '=', 'products.id')
        ->select('product_videos.*', 'products.name as product_name')
        ->get();
        return response()->json($videos);
    }
    public function store($video_link, $product_id, $user_id, $product_name, $platform){
       
        try{
            $video = ProductVideos::create([
                'name' => $product_name,
                'link' => $video_link,
                'plataform' => $platform,
                'user_id' => $user_id,
                'product_id'=>  $product_id, 
            ]);
            return response()->json($video);
        }
        catch(Exception $e){
            return response()->json($e);
        }
        
    }
    public function update(Request $request, $id){
        try{
            $video = ProductVideos::where('product_id', $id)->updateOrCreate([
                'name' => $request->name,
                'link' => $request->video_link,
                'plataform' => $request->platform,
                'user_id' => $request->user_id,
                'product_id'=>  $id, 
            ]);
           
            return response()->json($video);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function destroy($id){
        try{
            $product_video = Product::where('product_id', $id)->delete();
            return response()->json($product_video);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
}
