<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Services\OrderService\OrderService;
use Exception;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;
    public function __construct(OrderService $orderService){
        $this->orderService = $orderService;
    }
    public function index()
    {
        try {
           
            $orders = $this->orderService->init();
            return $orders;
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
    public function create(Request $request, $responseData)
    {
        try {
            $order = $this->orderService->store($request, $responseData);
           
            return response()->json($order);
        } catch (Exception $e) {

            return response()->json($e->getMessage(), 500);
        }

        return response()->json($order);
    }
   // public function insertOrderId(Request $request, $order)
   // {
   //     try {
   //         $customer = Auth::guard('customer')->user();
   //         
   //         $latest_order = Order::where('user_id', $customer->id)
   //             ->latest()
   //             ->first();
   //         $latest_order->update(['order_id' => $order['MerchantOrderId']]);
   //     
   //         return response()->json($latest_order);
   //     } catch (Exception $e) {
   //         return response()->json($e);
   //     }
   // }
    public function allOrders()
    {
        try {
            $orders = $this->orderService->getAll();
            return response()->json($orders);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
    public function getOrder($id){
        try{
            $order = $this->orderService->getOrder($id);
            return response()->json($order);
            
        }   
        catch(Exception $e){
            return response()->json($e->getMessage());
        }
    }
}
