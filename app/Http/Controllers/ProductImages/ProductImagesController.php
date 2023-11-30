<?php

namespace App\Http\Controllers\ProductImages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImages;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProductImagesController extends Controller
{
    //
    protected $user;
    public function __construct(User $user){
        $this->user = $user;
    }
    public function index(){
        $images = ProductImages::join('products', 'product_images.product_id', '=', 'products.id')
            ->select('product_images.*', 'products.name as product_name')
            ->get();
          
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
    public function update($upload_file, $id,Request $request){
        
        try{
            $imagesProducts = ProductImages::where('product_id', $id)->get();
                foreach ($upload_file as $image) {
                    foreach($imagesProducts as $productImg){
                        $path_parts = pathinfo($image);
                    
                        $name_file = $path_parts['filename']; // Contém o nome do arquivo (sem a extensão)
                        $extension = $path_parts['extension']; // Contém a extensão do arquivo
                        $updateProduct = ProductImages::where('id', $productImg['id'])->delete();
                     }
                    $product =  ProductImages::create([
                        'name' => $name_file,
                        'extension' => $extension,
                        'size' => '2mb',
                        'product_id' => $id,
                        'user_id' => $request->user_id
                    ]); 
                }
             
             return response()->json($product);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function destroy($id){
        try{
            $image = ProductImages::where('product_id')->get();
            foreach($image as $img){
                if($img){
                    $delete_file = Storage::delete('./storage/products/'.$img->name);
                    $delete_register = $image->delete();
                 }
            }
            return response()->json($image); 
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function destroyAll($id){
        try{
            $image = ProductImages::where('product_id', $id)->get();
           
            foreach($image as $img){
                $img->delete();
            }
            return response()->json($img);
        }
        catch(Exception $e){
            return response()->json($e);
        }
       
    }
}
