<?php

namespace App\Http\Controllers\Banner;

use App\Http\Controllers\Controller;
use App\Services\BannerService\BannerService;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    //
    protected $bannerService;
    public function __construct(BannerService $bannerService){
        $this->bannerService = $bannerService;
    }
    public function index(){
        $banner = $this->bannerService->getBannerImage();
        return response()->json($banner);
    }
}
