<?php

namespace App\Services\StoreService;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\AppBarService\AppBarService;
use App\Services\CardService\CardService;
use App\Services\BannerService\BannerService;
use App\Services\CarrouselService\CarrouselService;

class StoreService
{
    protected $store;
    protected $appBarService;
    protected $cardService;
    protected $bannerService;
    protected $carrouselService;

    public function __construct(
        Store $store,
        AppBarService $appBarService,
        CardService $cardService,
        BannerService $bannerService,
        CarrouselService $carrouselService,
    ) {
        $this->store = $store;
        $this->appBarService = $appBarService;
        $this->cardService = $cardService;
        $this->bannerService = $bannerService;
        $this->carrouselService = $carrouselService;
    }
    public function getStore()
    {
        $store = $this->store->first();
        return $store;
    }
    public function getAppIcon()
    {
        $app_icon = $this->store->select('app_logo')->first();
        return $app_icon;
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
            $upload_image = $this->uploadImg($request->file('app_logo'));
            $updateStore = $this->store->findOrFail($id)->update([
                'app_name' => $request->app_name,
                'app_address' => $request->app_address,
                'app_logo' => json_encode($upload_image),
                'app_mail' => $request->app_mail,
                'app_phone' => $request->app_phone,
                'app_address' => $request->app_address
            ]);

            return $updateStore;
        } catch (Exception $e) {
            return $e;
        }
    }
    public function getStyle($id)
    {
        $style = $this->appBarService->getAppBar($id);
        return $style;
    }
    public function styleStore($request)
    {
        try {
            $getStore = $this->getStore();
            $appBar = $this->createAppBar($request->appBarColor, $getStore->id);

            $card = $this->createCard($request->chipColor, $getStore->id);

            $uploadBannerImage = $this->uploadImgBanner($request->banner1[1]);
            $banner = $this->createBanner($uploadBannerImage, $getStore->id);

            /*continuar daqui do carrousel */
            $carrousel = [
                'banner2' => $request->banner2[1],
                'banner3' => $request->banner3[1],
                'banner4' => $request->banner4[1],
                'banner5' => $request->banner5[1]
            ];
            $upload_carrousel = $this->uploadImgCarrousel($carrousel);

            $createdCarrousel = $this->createCarrousel($upload_carrousel, $getStore->id);

            $response = [
                'colors' => $appBar['colors'],
                'chip_color' => $card['chip_color'],
                'banner_image' => json_encode($uploadBannerImage),
                'images' => json_encode($upload_carrousel),

            ];


            return response()->json($response);
        } catch (Exception $e) {
            return $e;
        }
    }
    public function updateStyleStore($request, $storeId)
    {
        try {
            $updateAppBar = $this->appBarService->update($request, $storeId);

            $updateCard = $this->cardService->update($request, $storeId);

            $bannerImage = $request->banner1[1]['name'];

            $uploadBannerImage = $this->uploadImgBanner($request->banner1[1]);

            $updateBanner = $this->bannerService->update($uploadBannerImage, $storeId);
            /*continuar daqui do carrousel */
            $carrousel = [
                'banner2' => $request->banner2[1],
                'banner3' => $request->banner3[1],
                'banner4' => $request->banner4[1],
                'banner5' => $request->banner5[1]
            ];
            $uploadImageCarrousel = $this->uploadImgCarrousel($carrousel);
            $updateCarrousel = $this->carrouselService->update($uploadImageCarrousel, $request->storeId);

            $response = [
                'colors' => $updateAppBar,
                'chip_color' => $updateCard,
                'banner_image' => json_encode($uploadBannerImage),
                'images' => json_encode($uploadImageCarrousel),

            ];


            return response()->json($response);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
    public function remove($storeId)
    {
        try {
            $deleteAppBar = $this->appBarService->delete($storeId);
            $deleteCard = $this->cardService->delete($storeId);
            $banner = $this->bannerService->delete($storeId);
            $carrousel = $this->carrouselService->delete($storeId);
            return true;
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
    public function createAppBar($color, $id)
    {
        try {
            $create = $this->appBarService->store($color, $id);
            return $create->toArray();
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function createCard($color, $id)
    {
        $create = $this->cardService->store($color, $id);
        return $create->toArray();
    }
    public function createBanner($image, $id)
    {
        $create = $this->bannerService->store($image, $id);
        return response()->json($create);
    }
    public function createCarrousel($images, $id)
    {
        $create = $this->carrouselService->store($images, $id);
        return response()->json($create);
    }
    public function uploadImgBanner($file)
    {
        foreach ($file as $img) {

            $fileName  = 'Banner1.webp';
            $path = Storage::putFileAs('/public/Banners/', $img, $fileName);
            return $fileName;
        }
        return $fileName;
    }
    public function uploadImgCarrousel($carrousel)
    {
        $randomNames = [];

        foreach ($carrousel as $img) {
            $randomName = Str::random(10) . '.webp';

            $fileName  = $img['src'];

            $path = Storage::putFileAs('/public/Carrousel', $fileName, $randomName);
            $randomNames[] = $randomName;
        }

        return $randomNames;
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
