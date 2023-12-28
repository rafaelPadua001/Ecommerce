<?php
namespace App\Services\ProfileImageService;

use App\Models\ProfileImage;

class ProfileImageService {
    protected $profileImage;

    public function __construct(ProfileImage $profileImage){
        $this->profileImage = $profileImage;
    }
    
}