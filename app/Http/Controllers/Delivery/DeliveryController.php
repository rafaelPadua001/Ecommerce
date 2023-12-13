<?php

namespace App\Http\Controllers\Delivery;


use App\Http\Controllers\Controller;
use App\Services\DeliveryService\DeliveryService;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    protected $deliveryService;
    public function __construct(DeliveryService $deliveryService){
        $this->deliveryService = $deliveryService;
    }
    public function index(){
        $delivery = $this->deliveryService->getall();
        return response()->json($delivery);
    }
    public function store(Request $request){
        
        $delivery_store = $this->deliveryService->store($request);
        
        return response()->json($delivery_store);
    }
}
