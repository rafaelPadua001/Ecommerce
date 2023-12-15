<?php
namespace App\Services\ShippmentService;

use App\Models\Shippment;
use Exception;

class ShippmentService { 
    protected $shippment;
    public function __construct(Shippment $shippment){
        $this->shippment = $shippment;
    }
    public function getAll(){
        try{
            $shippments = $this->shippment->all();
            return $shippments;
        }
        catch(Exception $e){
            return response()->json($e->getMessage());
        }
       
    }
    public function store($delivery_item){
      
        $data = [
            'name' => $delivery_item['delivery_name'],
            'company' => $delivery_item['delivery']['0']['company']['name'],
            'price' => $delivery_item['delivery']['0']['packages']['0']['price'],
            'deadline' => $delivery_item['delivery']['0']['delivery_time'],
            'product_id' => $delivery_item['product_id'],
            'quantity' => $delivery_item['delivery']['0']['packages']['0']['products']['0']['quantity'],
            'user_id' => $delivery_item['user_id'],
            'cart_id' => $delivery_item['cart_id'],
            'cart_item_id' => $delivery_item['cart_item_id'],
            'delivery_id' => $delivery_item['delivery']['0']['packages']['0']['products']['0']['id']
        ];

        
        $store_shippment = $this->shippment->create($data);
        
        return $store_shippment;
    }
   

} 