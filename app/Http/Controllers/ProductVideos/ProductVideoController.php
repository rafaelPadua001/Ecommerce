<?php

namespace App\Http\Controllers\ProductVideos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductVideos;
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
}
