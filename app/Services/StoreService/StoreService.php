<?php

namespace App\Services\StoreService;

use App\Models\Store;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\AppBarService\AppBarService;
class StoreService
{
    protected $store;
    protected $appBarService;
    public function __construct(
        Store $store,
        AppBarService $appBarService
    )
    {
        $this->store = $store;
        $this->appBarService = $appBarService;    
    }
    public function getStore()
    {
        $store = $this->store->first();
        return $store;
    }
    public function store(Request $request)
    {
        try {
            $file = $request->file('app_logo');
            $upload_image = $this->uploadImg($file);
            $user = Auth::user();

            $store = $this->store->create([
                'app_name' => $request->app_name,
                'app_mail' => $request->app_mail,
                'app_logo' => json_encode($upload_image),
                'app_phone' => $request->app_phone,
                'app_address' => $request->app_address,
                'user_id' => $user->id
            ]);

            return $store;
        } catch (Exception $e) {
            return $e;
        }
    }
   
    public function uploadImg($file)
    {

        foreach ($file as $img) {
            $fileName = 'Logos/app_icon.' . $img->Extension();

            $path = Storage::putFileAs('/public/app_icon/', $img, $fileName);

            return $fileName;
        }
    }
    public function updateStore($id, $request)
    {
        try {

            $updateStore = $this->store->findOrFail($id)->update($request->all());
            //dd($request->all());
            return $request->all();
        } catch (Exception $e) {
            return $e;
        }
    }
    public function styleStore($request)
    {
       try {
            $getStore = $this->getStore();
            $appBar = $this->createAppBar($request->appBarColor, $getStore->id);
            
            dd($appBar);
            $banner1 = $request->banner1[1];
            
           
            
            $uploadBannerImage = $this->uploadImgBanner($banner1);

            $carrousel = [
                'banner2' => $request->banner2[1],
                'banner3' => $request->banner3[1],
                'banner4' => $request->banner4[1],
                'banner5' => $request->banner5[1]
            ];
            $upload_carrousel = $this->uploadImgCarrousel($carrousel);
            dd($upload_carrousel);
            return response()->json($upload_carrousel);
        } catch (Exception $e) {
            return $e;
        }
    } 
    public function createAppBar($color, $id){
        try{
            $create = $this->appBarService->store($color, $id);
            return response()->json($create);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function uploadImgBanner($file)
    {
        foreach ($file as $img) {

            $fileName  = 'Banner1.webp';
            $path = Storage::putFileAs('/public/Banners/', $img, $fileName);
            return $fileName;
        }
    }
    public function uploadImgCarrousel($carrousel)
    {

        foreach ($carrousel as $img) {

            $fileName  = $img['name'];

            $path = Storage::putFileAs('/public/Carrousel/', $img['src'], $fileName);
        }

        return $fileName;
    }
    public function destroy($id)
    {
        try {
            $destroy = $this->store->findOrFail($id)->delete();
            return $destroy;
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
}
