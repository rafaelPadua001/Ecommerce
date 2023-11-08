<?php

namespace App\Http\Controllers\MelhorEnvio;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Orders\OrderController;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MelhorEnvio\Shipment;
use MelhorEnvio\Enums\Environment;
use MelhorEnvio\Resources\Shipment\Product;




class MelhorEnvioController extends Controller
{
    //
    public function calculateDelivery(Request $request)
    {
        try {

            $client_postal_code = str_replace('-', '', $request->postal_code);

            $calculator = $this->createShipmentInstance()->calculator();

            $calculator->postalCode(env('CEP_CLIENT'), $client_postal_code);

            $calculator->setOwnHand();
            $calculator->setReceipt(false);
            $calculator->setCollect(false);

            $height = intval($request->height);
            $width = intval($request->width);
            $length = intval($request->length);
            $weight = $request->weight;
            $price = $request->price;
            $quantity = $request->quantity;

            $calculator->addProducts(
                $product = new Product(uniqid(), $height, $width, $length, $weight, 50.00 /*$price*/, $quantity),

            );

            $quotations = $calculator->calculate();

            // dd($quotations);
            return response()->json($quotations);
        } catch (Exception $e) {
            return response()->json($e);
        }

        return response()->json($quotations);
    }

    public function createShipmentInstance()
    {
        $shipment = new Shipment(env('MELHORENVIO_ACCESS_TOKEN', Environment::SANDBOX));
        return $shipment;
    }
    public function createCart(Request $request){
        $client = new Client();
        $customer = Auth::guard('customer')->user();
        
       // $address = Address::where('')
       
        try {
            $response = $client->post('https://sandbox.melhorenvio.com.br/api/v2/me/cart', [
                'json' => [
                    'service' => $request['delivery']['id'],
                    'agency' => $request['delivery']['company']['id'],
                    'from' => [
                        'name' => env('APP_NAME'),
                        "phone"=> 556195051731, //env('EMPLOYE_PHONE'),
                        "email"=> env('EMPLOYE_MAIL'),
                        "address"=> env('EMPLOYE_ADDRESS'),
                        "city"=> env('EMPLOYE_CITY'),
                        "postal_code"=> env('EMPLOYE_POSTALCODE'),
                        "document" => env('EMPLOYE_DOCUMENT')
                        // Preencha os detalhes de origem aqui
                    ],
                    'to' => [
                        // Preencha os detalhes de destino aqui
                        "name" => $customer->fist_name . $customer->last_name,
                        "phone" => $request['address']['telefone'],
                          "email"   => $customer->email,
                          "address" => $request['address']['endereco'],
                          "complement" => $request['address']['complemento'],
                          "city" => $request['address']['cidade'],
                          "postal_code" => $request['address']['cep'],
                          "state_abbr" => $request['address']['UF'],
                          'document' => $request['document']
                    ],
                    'products' => [
                        [
                            'name' => $request->name,
                            'quantity' => $request->quantity,
                            'unitary_value' => $request->totalValue
                        ]
                       //  Adicione mais produtos conforme necessário
                    ],
                    'volumes' => [
                        [
                            'height' => $request['delivery']['packages'][0]['dimensions']['height'],
                            'width' => $request['delivery']['packages'][0]['dimensions']['width'],
                            'length' => $request['delivery']['packages'][0]['dimensions']['length'],
                            'weight' => $request['delivery']['packages'][0]['weight']
                        ]
                        // Adicione mais volumes conforme necessário
                    ],
                    'options' => [
                        'insurance_value' => $request['delivery']['packages'][0]['insurance_value'],
                        'receipt' => $request['delivery']['additional_services']['receipt'],
                        'own_hand' => $request['delivery']['additional_services']['own_hand'],
                        'reverse' => true,
                        'non_commercial' => true,
                       
                    ]
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . env('MELHORENVIO_ACCESS_TOKEN'), //  Substitua com seu token do Melhor Envio
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'rafael.f.p.faria@hotmail.com',
                ],
            ]);
           
            //echo $response->getBody();
            $order = json_decode($response->getBody());
            
                
            $this->getOrder($order, $request);
            
            
            return response()->json($response);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
    public function getOrder($order, $request){
        $orders = new OrderController();
        return $orders->insertOrderId($request, $order);
    }
    public function generateTicket(Request $request)
    {
        dd($request);
    }
    
}
