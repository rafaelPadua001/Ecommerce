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
use App\Models\Order;
use stdClass;

class MelhorEnvioController extends Controller
{
    //
    public function calculate($request)
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
    public function createCart(Request $request)
    {
        $client = new Client();
        $customer = Auth::guard('customer')->user();

        try {
          //  dd($request['address']['shippment_address'], $customer, env('EMPLOYE_MAIL'));
            $response = $client->post('https://sandbox.melhorenvio.com.br/api/v2/me/cart', [
                'json' => [
                    'service' =>  17,
                  //  'agency' =>  0,
                    'from' => [
                        'name' => env('APP_NAME'),
                        "phone" => 556195051731, //env('EMPLOYE_PHONE'),
                        "email" => env('EMPLOYE_MAIL'),
                        "address" => env('EMPLOYE_ADDRESS'),
                        "city" => env('EMPLOYE_CITY'),
                        "postal_code" => env('EMPLOYE_POSTALCODE'),
                        "document" => env('EMPLOYE_DOCUMENT')
                        // Preencha os detalhes de origem aqui
                    ],
                    // 'to' => [
                    //     // Preencha os detalhes de destino aqui
                    //     "name" => $customer->first_name . $customer->last_name,
                    //     "phone" => $request['telefone'],
                    //     "email"   => $customer->email,
                    //     "address" => $request['address']['shippment_address'],
                    //     "complement" => $request['address']['complemento'] ?? $request['address']['shippment_complement'],
                    //     "city" => $request['adddress']['shippment_city'],
                    //     "postal_code" =>  $request['address']['zip_code'],
                    //     "state_abbr" => $request['adddress']['select_uf']['uf'],
                    //     'document' => $request['document']
                    // ],
                    // 'products' => $this->createProduct($request),
                    // 'products' => [
                    //     [
                    //         'name' => $request->name,
                    //         'quantity' => $request->quantity,
                    //         'unitary_value' => $request->totalValue
                    //     ]
                    //    //  Adicione mais produtos conforme necessário
                    // ],
                    // 'volumes' => $this->createVolumes($request),
                    // 'volumes' => [
                    //     [
                    //         'height' => $request['delivery'][0]['packages'][0]['dimensions']['height'],
                    //         'width' => $request['delivery'][0]['packages'][0]['dimensions']['width'],
                    //         'length' => $request['delivery'][0]['packages'][0]['dimensions']['length'],
                    //         'weight' => $request['delivery'][0]['packages'][0]['weight']
                    //     ]
                    //     // Adicione mais volumes conforme necessário
                    // ],
                    // 'options' => [
                    //     'insurance_value' => $request['delivery'][0]['packages'][0]['insurance_value'],
                    //     'receipt' => $request['delivery'][0]['additional_services']['receipt'],
                    //     'own_hand' => $request['delivery'][0]['additional_services']['own_hand'],
                    //     'reverse' => true,
                    //     'non_commercial' => true,

                    // ]
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . env('MELHORENVIO_ACCESS_TOKEN'), //  Substitua com seu token do Melhor Envio
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'rafael.f.p.faria@hotmail.com',
                ],
            ]);

            dd($response);
            $orderShippment = json_decode($response->getBody()->getContents(), true);
            $mergedData = array_merge($orderShippment, $request->toArray());



            //$this->getOrder($order, $request);


            return $mergedData;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
    public function createProduct($request)
    {
        $product = $request->input('cartItem');
        dd('Product: ' . $product);
        $products = [];
        foreach ($product as $item) {
            return $products[] = [
                'id' => $item['cart_item_id'],
                'name' => $item['name'],
                'quantity' => $item['shippment_quantity'],
                'unitary_value' => $item['total_price']
            ];
        }
    }
    public function createVolumes($request)
    {
        $product = $request->input('cartItem');
        dd('Product:' . $product);
        $volumes = [];
        foreach ($product as $item) {
            return $volumes[] = [
                'height' => $item['height'],
                'width' => $item['width'],
                'length' => $item['length'],
                'weight' => $item['weight']
            ];
        }
    }
    public function checkout(Request $request)
    {
        //dd($request->order);
        try {
            $client = $this->getClient();
            $customer = Auth::guard('customer')->user();
            $response = $client->request('POST', 'https://sandbox.melhorenvio.com.br/api/v2/me/shipment/checkout', [
                'json' => ['orders' => [$request->order]],
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . env('MELHORENVIO_ACCESS_TOKEN'), // Substitua pelo seu token
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'rafael.f.p.faria@hotmail.com',
                ],
            ]);
            if ($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody()->getContents(), true);
                // Verifique o conteúdo de $responseData
                return response()->json($responseData);
            } else {
                return response()->json(['error' => 'Erro na requisição'], $response->getStatusCode());
            }
            // echo $response->getBody();
            $body = $response->getBody()->getContents();

            // Convertemos a string JSON para um array associativo
            $data = json_decode($body, true);

            // Agora $data contém a resposta da API em formato de array
            return response()->json($data);
            //  return response()->json($response);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function generatePrint(Request $request)
    {

        try {
            $client = $this->getClient();
            $customer = Auth::guard('customer')->user();
            $response = $client->request('POST', 'https://sandbox.melhorenvio.com.br/api/v2/me/shipment/generate', [
                'json' => ['orders' => [$request->order['delivery_id']]],
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . env('MELHORENVIO_ACCESS_TOKEN'), // Substitua pelo seu token
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'rafael.f.p.faria@hotmail.com',
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody()->getContents(), true);
                // Verifique o conteúdo de $responseData
                return response()->json($responseData);
            } else {
                return response()->json(['error' => 'Erro na requisição'], $response->getStatusCode());
            }
            // echo $response->getBody();
            $body = $response->getBody()->getContents();

            // Convertemos a string JSON para um array associativo
            $data = json_decode($body, true);

            // Agora $data contém a resposta da API em formato de array
            return response()->json($data);
            //  return response()->json($response);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getPrint(Request $request)
    {
        try {
            $client = $this->getClient();
            $customer = Auth::guard('customer')->user();
            $response = $client->request('POST', 'https://sandbox.melhorenvio.com.br/api/v2/me/shipment/print', [
                'json' => ['orders' => [$request->order['delivery_id']]],
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . env('MELHORENVIO_ACCESS_TOKEN'), // Substitua pelo seu token
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'rafael.f.p.faria@hotmail.com',
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody()->getContents(), true);
                // Verifique o conteúdo de $responseData
                return response()->json($responseData);
            } else {
                return response()->json(['error' => 'Erro na requisição'], $response->getStatusCode());
            }
            // echo $response->getBody();
            $body = $response->getBody()->getContents();

            // Convertemos a string JSON para um array associativo
            $data = json_decode($body, true);

            // Agora $data contém a resposta da API em formato de array
            return response()->json($data);
            //  return response()->json($response);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function tracking(Request $request)
    {

        try {
            $client = $this->getClient();

            $response = $client->request('POST', 'https://sandbox.melhorenvio.com.br/api/v2/me/shipment/tracking', [
                'json' => ['orders' =>  [$request->order['delivery_id']]],
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . env('MELHORENVIO_ACCESS_TOKEN'), // Substitua pelo seu token
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'rafael.f.p.faria@hotmail.com',
                ],
            ]);

            $body = $response->getBody()->getContents();
            return response()->json(json_decode($body));
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function destroy($id)
    {
        try {
            $order = Order::where('id', $id)->delete();
            return response()->json($order);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function getClient()
    {
        $client = new Client();
        return $client;
    }
    //public function getOrder($order, $request){
    //   
    //    $orders = new OrderController();
    //    return $orders->insertOrderId($request, $order);
    //}

}
