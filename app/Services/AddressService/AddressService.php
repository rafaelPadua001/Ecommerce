<?php
namespace App\Services\AddressService;

use App\Models\Address;

class AddressService{
    protected $address;
    
    public function __construct(Address $address){
        $this->address = $address;
    }
    

}