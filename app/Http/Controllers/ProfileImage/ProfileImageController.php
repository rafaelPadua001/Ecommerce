<?php

namespace App\Http\Controllers\ProfileImage;

use App\Http\Controllers\Controller;
use App\Models\ProfileImage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileImageController extends Controller
{
    //
    public function index(){
        $customer = Auth::guard('customer')->user();
        $image = ProfileImage::where('customer_id', $customer->id)->latest()->first();
        return response()->json($image);
    }
    public function upload(Request $request){
        try{
            $customer = Auth::guard('customer')->user();
            $image = $request->file('image');
            
            $this->store($image, $customer);
            $this->storeInDirectory($image);
           return response()->json($image);
         
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function storeInDirectory($image){
        try{
            $path = [];
            foreach($image as $img){
                $path = Storage::putFileAs('public/avatars', $img, $img->getClientOriginalName());
                return response()->json($img);
            }
           return response()->json($image);
        }
        catch(Exception $e){
            return response()->json($e);
        }
       
    }
    public function store($image, $customer){
        try{
            foreach($image as $img){
                $uploadFile = ProfileImage::updateOrCreate([
                    'name' => $img->getClientOriginalName(),
                    'extension' =>  $img->getClientOriginalExtension(),
                    'size' => $img->getClientOriginalExtension(),
                    'customer_id' => $customer->id
                ]);
            }
            

            return response()->json($uploadFile);
        }
        catch(Exception $e){
            return response()->json($e);
        }
        
    }
    public function destroy($id){
        try{
            $profileImage = ProfileImage::findOrFail($id)->delete();
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
}
