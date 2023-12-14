<?php
namespace App\Services\Shippment;

use App\Models\Shippment;


class ShippmentService{
    protected $shippment;
    public function __construct(Shippment $shippment){
        $this->shippment = $shippment;
    }
    public function store($request){
       dd($request);
        $store_shippment = $this->shippment->create([$request]);

        return $store_shippment;
    }
   

} 