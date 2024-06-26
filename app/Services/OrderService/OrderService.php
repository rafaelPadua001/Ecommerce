<?php

namespace App\Services\OrderService;

use App\Models\Order;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Exception;

class OrderService
{
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
    public function init()
    {
        try {
            $customer = Auth::guard('customer')->user();
            $orders = $this->order->where('user_id', $customer->id)->get();
            return $orders;
        } catch (Exception $e) {
            return $e;
        }
    }
    public function store($request, $itemNameJson, $responseData)
    {
        
        try {
            $request_data = $request->input();
          
            $response = $responseData;
           
            $customer = Auth::guard('customer')->user();
            $cartItems = $request->input('cartItem');
            $colors = [];
            foreach($cartItems as $item){
                $colors[] = $item['cart_item_colors'];
            }
            $colorsJSON = json_encode($colors);
           
            $order = $this->order->create([
                'client' => $customer->first_name . ' ' . $customer->last_name,
                'products' => $itemNameJson,    
                'color' => $colorsJSON,
                'exec_date' => $response['Payment']['ReceivedDate'],
                'value' => $request['totalValue'],
                'status' => 'open',
                'user_id' => $customer->id,
                //'cart_item_id' => $request->cartItem_id,
                'address' => $request_data['address']['shippment_address'],
                'cep' => $request_data['address']['zip_code'] ?? $request_data['address']['postal_code'],
                'complemento' => $request_data['address']['complemento'] ?? $request_data['address']['shippment_complement'],
                'bairro' => $request_data['address']['bairro'] ?? 'not register',
                'cidade' => $request_data['address']['cidade'] ?? $request_data['address']['shippment_city'],
                'uf' => $request_data['address']['uf'] ?? $request_data['address']['select_uf']['uf'],
                'pais' => $request_data['address']['pais'] ?? 'Brasil',
            ]);
            
            $insertId = $this->createOrderId($request, $responseData);
           
            return response()->json($order);
        } catch (Exception $e) {

            return response()->json($e);
        }

        return response()->json($order);
    }
    public function createOrderId($request, $order)
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
    public function getAll()
    {
        try {
            $orders = $this->order->all();

            return $orders;
        } catch (Exception $e) {
            return $e;
        }
    }
    public function getOrder($id)
    {
        try {
            $order = $this->order->findOrFail($id);
            $merchantOrderId = $order->order_id;

            $client = new Client();
            $merchantId = env('CIELO_MERCHANT_ID');
            $merchantKey = env('CIELO_MERCHANT_KEY');

            $url = 'https://apiquerysandbox.cieloecommerce.cielo.com.br/1/sales?merchantOrderId=' . $merchantOrderId;

            $headers = [
                'Content-Type: application/json',
                'MerchantId' => $merchantId,
                'MerchantKey' => $merchantKey,
            ];

            $response = $client->request('GET', $url, [
                'headers' => $headers,
            ]);

            // $stausCode = $response->getStatusCode();
            $data = json_decode($response->getBody()->getContents(), true);

            return response()->json($data);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
    public function transactionSearch($id)
    {
        try {
            $client = new Client();
            $merchantId = env('CIELO_MERCHANT_ID');
            $merchantKey = env('CIELO_MERCHANT_KEY');

            $url = 'https://apiquerysandbox.cieloecommerce.cielo.com.br/1/sales/' . $id;

            $headers = [
                'Content-Type: application/json',
                'MerchantId' => $merchantId,
                'MerchantKey' => $merchantKey,
            ];

            $response = $client->request('GET', $url, [
                'headers' => $headers,
            ]);

            // $stausCode = $response->getStatusCode();
            $data = json_decode($response->getBody()->getContents(), true);

            return $data;
        } catch (Exception $e) {
            return $e;
        }
    }
   
    public function refund($id, $amount){
        
        try {
            $client = new Client();
            $merchantId = env('CIELO_MERCHANT_ID');
            $merchantKey = env('CIELO_MERCHANT_KEY');

            $url = 'https://apiquerysandbox.cieloecommerce.cielo.com.br/1/sales/48a51b3f-7820-4908-85ad-7854c30aae19/void';
           
            $headers = [
                'Content-Type: application/json',
                'MerchantId' => $merchantId,
                'MerchantKey' => $merchantKey,
            ];

            $response = $client->request('PUT', $url, [
                'headers' => $headers,
            ]);
            
           
            $data = json_decode($response->getBody()->getContents(), true);
            
            return $data;
        } catch (Exception $e) {
            return $e;
        }
    }
    public function updateStatus($id, $request){
        try{
            $order = $this->order->findOrFail($id)->update(['status' => 'close']);
            return $this->order->findOrFail($id);
        }
        catch(Exception $e){
            return $e;
        }
    }
}
