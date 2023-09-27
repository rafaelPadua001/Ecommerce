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
        $videos = ProductVideos::all();
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
    public function destroy($id){
        $video = ProductVideos::findOrFail($id);
        $product_video = Product::where('id', $video->product_id);
        try{
            $video->delete();
            $remove_video_product = $product_video->update([
                'video' => null,
            ]);
            return response()->json();
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
}
