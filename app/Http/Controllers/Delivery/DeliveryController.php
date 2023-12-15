<?php

namespace App\Http\Controllers\Delivery;


use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Services\DeliveryService\DeliveryService;
use Exception;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    protected $deliveryService;
    protected $delivery;
    public function __construct(DeliveryService $deliveryService, Delivery $delivery){
        $this->delivery = $delivery;
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
    public function calculateDelivery(Request $request){
        try{
            $shippment_company = $this->delivery->where('name', '=', $request->shippment)->first();
            return $this->deliveryService->calculate($request, $shippment_company,);
        }
        catch(Exception $e){
            return response()->json($e);
        }   
      
    }
    public function delete($id){
        $delivery_delete = $this->deliveryService->destroy($id);
        return response()->json($delivery_delete);
    }
    
}
