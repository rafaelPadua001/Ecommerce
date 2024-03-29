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
    public function getTransaction($id){
        try{
            $transaction = $this->orderService->transactionSearch($id);
            return response()->json($transaction);
        }
        catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }
    public function refundTransaction($id, Request $request){
        try{
            
            $amount = $request->amount;
            $refund = $this->orderService->refund($id, $amount);
            dd($refund);
            return response()->json($refund);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function alterStatus($id, Request $request){
        try{
            $order = $this->orderService->updateStatus($id, $request);
            return response()->json($order);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }
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
