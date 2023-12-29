<?php

namespace App\Http\Controllers\ProfileImage;

use App\Http\Controllers\Controller;
use App\Models\ProfileImage;
use App\Services\ProfileImageService\ProfileImageService;
use Exception;
use Illuminate\Http\Request;


class ProfileImageController extends Controller
{
    protected $profileImageService;

    public function __construct(ProfileImageService $profileImageService)
    {
        $this->profileImageService = $profileImageService;
    }
    public function index()
    {
        $image =  $this->profileImageService->getImage();
        return response()->json($image);
    }
    public function upload(Request $request)
    {
        try {
            $upload = $this->profileImageService->uploadAvatar($request);

            return response()->json($upload);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function destroy($id)
    {
        try {
            $profileImage = ProfileImage::findOrFail($id)->delete();
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
}
