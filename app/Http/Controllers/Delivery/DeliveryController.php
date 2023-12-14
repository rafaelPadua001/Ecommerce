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
    public function status(Request $request, $id){
        
        $data = [
            'status' => $request->status,
            'id' => $id
        ];
        $delivery_status = $this->deliveryService->alterStatus($data);

        return $delivery_status;
    }
    public function delete($id){
        $delivery_delete = $this->deliveryService->destroy($id);
        return response()->json($delivery_delete);
    }
}
