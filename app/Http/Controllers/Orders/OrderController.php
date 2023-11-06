<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
  
    public function index(){
        try{
            $customer = Auth::guard('customer')->user();
            $orders = Order::where('user_id', $customer->id)->get();
            return response()->json($orders);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function create(Request $request, $responseData)
    {
        try {
            $request_data = $request->input();
           
            $response = $responseData;
            $customer = Auth::guard('customer')->user();
           //  dd($response['Payment']['ReceivedDate']); 
            $order = Order::create([
                'client' => $customer->first_name . ' ' . $customer->last_name,
                'description' => $request_data['description'],
                'color' => json_encode($request_data['color']),
                'exec_date' => $response['Payment']['ReceivedDate'],
                'value' => $request['totalValue'],
                'status' => 'open',
                'user_id' => $customer->id,
                'cart_item_id' => $request->id
            ]);
           
            return response()->json($order);
        } catch (Exception $e) {
            dd($e->getMessage());
            return response()->json($e);
        }

        return response()->json($order);
    }
}
