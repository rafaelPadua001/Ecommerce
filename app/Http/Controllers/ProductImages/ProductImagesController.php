<?php

namespace App\Http\Controllers\ProductImages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductImages;
use Exception;
use Illuminate\Support\Facades\Storage;

class ProductImagesController extends Controller
{
    //
    public function index(){
        $images = ProductImages::all();
        return response()->json($images);
    }
    public function store($upload_file, $product_id, $user_id)
    {
        try {
            foreach ($upload_file as $image) {

                $path_parts = pathinfo($image);

                $name_file = $path_parts['filename']; // Contém o nome do arquivo (sem a extensão)
                $extension = $path_parts['extension']; // Contém a extensão do arquivo

                $image = ProductImages::create([
                    'name' => $name_file,
                    'extension' => $extension,
                    'size' => '2mb',
                    'product_id' => $product_id,
                    'user_id' => $user_id,
                ]);

            }
            return response()->json($image);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function destroy($id){
        try{
            $image = ProductImages::findOrFail($id);
            if($image){
                $delete_file = Storage::delete('./storage/products/'.$image->name);
                $delete_register = $image->delete();
                
            }

            return response()->json(); 
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
}
