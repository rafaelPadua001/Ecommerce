<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;


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
           
            $order = Order::create([
                'client' => $customer->first_name . ' ' . $customer->last_name,
                'description' => $request_data['description'],
                'color' => json_encode($request_data['color']),
                'exec_date' => $response['Payment']['ReceivedDate'],
                'value' => $request['totalValue'],
                'status' => 'open',
                'user_id' => $customer->id,
                'cart_item_id' => $request->cartItem_id,
                'address' => $request_data['address']['endereco'],
                'cep' => $request_data['address']['cep'] ?? $request_data['address']['postal_code'],
                'complemento' => $request_data['address']['complemento'],
                'bairro' => $request_data['address']['bairro'],
                'cidade' => $request_data['address']['cidade'],
                'uf' => $request_data['address']['uf'],
                'pais' => $request_data['address']['pais'] ?? 'Brasil',
            ]);
            $insertId = $this->insertOrderId($request, $responseData);
           
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
            
            $latest_order = Order::where('user_id', $customer->id)
                ->latest()
                ->first();
            $latest_order->update(['order_id' => $order['MerchantOrderId']]);
        
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
    public function getOrder($id){
        try{
            $order = Order::findOrFail($id);
            $merchantOrderId = $order->order_id;

            $client = new Client();
            $merchantId = env('CIELO_MERCHANT_ID');
            $merchantKey = env('CIELO_MERCHANT_KEY');

            $url = 'https://apiquerysandbox.cieloecommerce.cielo.com.br/1/sales?merchantOrderId=' . $merchantOrderId;

            $headers = [
                'Content-Type: application/son',
                'MerchantId' => $merchantId,
                'MerchantKey' => $merchantKey,
            ];

            $response = $client->request('GET', $url, [
                'headers' => $headers,
            ]);

            $stausCode = $response->getStatusCode();
            $data = json_decode($response->getBody()->getContents(), true);
            
            return response()->json($data);
            
        }   
        catch(Exception $e){
            return response()->json($e->getMessage());
        }
    }
}
