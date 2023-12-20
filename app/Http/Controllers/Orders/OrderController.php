<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function index()
    {
        try {
            $customer = Auth::guard('customer')->user();
            $orders = Order::where('user_id', $customer->id)->get();
            return response()->json($orders);
        } catch (Exception $e) {
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
                'cart_item_id' => $request->id,
                'address' => $request_data['address']['endereco'],
                'cep' => $request_data['address']['cep'] ?? $request_data['address']['postal_code'],
                'complemento' => $request_data['address']['complemento'],
                'bairro' => $request_data['address']['bairro'],
                'cidade' => $request_data['address']['cidade'],
                'uf' => $request_data['address']['uf'],
                'pais' => $request_data['address']['pais'] ?? 'Brasil',
            ]);

            return response()->json($order);
        } catch (Exception $e) {

            return response()->json($e);
        }

        return response()->json($order);
    }
    public function insertOrderId(Request $request, $order)
    {
        try {
            $customer = Auth::guard('customer')->user();
            $id = $order->id;

            $latest_order = Order::where('user_id', $customer->id)
                ->latest()
                ->first();
            $latest_order->update(['order_id' => $id]);

            return response()->json($latest_order);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function allOrders()
    {
        try {
            $orders = Order::all();

            return response()->json($orders);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
