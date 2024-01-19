<?php

namespace App\Services\StoreService;

use App\Models\Store;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Storage;

class StoreService {
    protected $store;

    public function __construct(Store $store){
        $this->store = $store;
    }
    public function store(Request $request){
        try{
            $file = $request->file('app_logo');
            $upload_image = $this->uploadImg($file);
           
          
            $store = $this->store->create([
                'app_name' => $request->app_name,
                'app_mail' => $request->app_mail,
                'app_logo' => json_encode($upload_image),
                'app_phone' => $request->app_phone,
                'app_address' => $request->app_address
            ]);
          
            return $store;
        }
        catch(Exception $e){
            return $e;
        }
        
    }
    public function uploadImg($file){
      
        foreach($file as $img){
            $fileName = 'app_icon.'.$img->Extension();
            
            $path = Storage::putFileAs('/public/app_icon/', $img, $fileName);

           return $fileName;
        }
    }
}