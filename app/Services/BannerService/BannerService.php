<?php
namespace App\Services\BannerService;

use App\Models\Banner;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Storage;

class BannerService {
    protected $banner;
    public function __construct(Banner $banner){
        $this->banner = $banner;
    }
    public function getBanner($id){
        try{
            $banner = $this->banner->where('store_id', '=', $id)->get();
            return $banner;
        }
        catch(Exception $e){
            return $e;
        }
    }
    public function getBannerImage(){
        try{
            $banner = $this->banner->first();
         
            return $banner;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
        
    }
    public function store($request, $id){
        try{
            $user = Auth::user();
            $store = $this->banner->create([
                'user_id' => $user->id,
                'store_id' => $id,
                'image' => json_encode($request),
            ]);

            return $store;
        }
        catch(Exception $e){
            return $e;
        }
    }
    public function update($request, $storeId){
      
        try{
            $user = Auth::user();
            $bannerUpdate = $this->banner->where('store_id', '=', $storeId)->first();
           
            $update = $this->banner->where('store_id', '=', $storeId)->update([
               'user_id' => $user->id,
               'store_id' => $request->storeId,
               'image' => json_encode($request),
           ]);
           
           return $update;
        }
        catch(Exception $e){
            return $e;
        }
    }
    public function remove($bannerImage){
        
        try{
            $removeFile = Storage::delete('/public/Banners/Banner1.webp');
            return $removeFile;
        }
        catch(Exception $e){
            return $e;
        }
      
    }
    public function delete($storeId){
        try{
            $this->banner->remove();
            $banner = $this->banner->where('store_id', '=', $storeId)->delete();
            return true;    
        }
        catch(Exception $e){
            return $e;
        }
    }   
}