<?php

namespace App\Services\ProfileImageService;

use App\Models\ProfileImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Exception;

class ProfileImageService
{
    protected $profileImage;

    public function __construct(ProfileImage $profileImage)
    {
        $this->profileImage = $profileImage;
    }
    public function getImage()
    {
        $customer = Auth::guard('customer')->user();
        $image = ProfileImage::where('customer_id', $customer->id)->latest()->first();
        return $image;
    }
    public function uploadAvatar($request)
    {
        try {
            $customer = Auth::guard('customer')->user();
            $image = $request->file('image');

            $upload = $this->store($image, $customer);
            $this->storeInDirectory($image);
            return $upload;
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function storeInDirectory($image)
    {
        try {
            $path = [];
            foreach ($image as $img) {
                $path = Storage::putFileAs('public/avatars', $img, $img->getClientOriginalName());
                return response()->json($img);
            }
            return response()->json($image);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function store($image, $customer)
    {
        try {
            foreach ($image as $img) {
                $uploadFile = ProfileImage::updateOrCreate([
                    'name' => $img->getClientOriginalName(),
                    'extension' =>  $img->getClientOriginalExtension(),
                    'size' => $img->getClientOriginalExtension(),
                    'customer_id' => $customer->id
                ]);
            }
            return response()->json($uploadFile);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
}
