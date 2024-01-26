<?php
namespace App\Services\BannerService;

use App\Models\Banner;
use Illuminate\Support\Facades\Auth;
use Exception;

class BannerService{
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
}