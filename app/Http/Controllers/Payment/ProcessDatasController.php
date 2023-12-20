<?php

namespace App\Http\Controllers\Payment;


use App\Http\Controllers\Controller;
use App\Http\Controllers\MelhorEnvio\MelhorEnvioController;
use App\Models\Payment;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;
use App\Http\Controllers\Orders\OrderController;

class ProcessDatasController extends Controller
{
    //public function getDatas(Request $request)
    //{
//
    //    $paymentType = $request->paymentType;
    //    if ($paymentType == 'credit') {
    //        return $this->creditPayment($request);
    //    } else if ($paymentType == 'debit') {
    //        return $this->debitPayment($request);
    //    } else {
    //        return $this->pixPayment($request);
    //        // throw new Exception('Ainda não criamos isso !');
    //    }
    //}
   /* public function creditPayment(Request $request)
    {
        
        try {
            $getAccess = $this->getAccess();
            $getClient = $this->getClient();
            $customer = Auth::guard('customer')->user();

            if ($getAccess) {
                try {
                    $client = new PreferenceClient();
                    $preference = $client->create([
                        "items" => array(
                            array(
                                "title" => $request->name,
                                "description" => $request->description,
                                "image" => getenv('APP_URL') . '/' . $request->image,
                                "quantity" => $request->quantity,
                                "currency_id" => "BRL",
                                "unit_price" => (float) $request->totalValue,
                                'notification_url' => env('APP_URL'),
                                'back_urls' => env('APP_URL')
                            )
                        )
                    ]);
                } catch (Exception $e) {
                    return response()->json($e);
                }
            }
             return $this->createPayment($preference, $request);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    /*public function debitPayment(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        
        try {
            //Aqui usaremos o guzzle para fazer as requisições HTTP
            $client = new Client([
                'base_uri' => 'https://apisandbox.cieloecommerce.cielo.com.br',
                'headers' => [
                    'Content-Type' => 'application/json',
                    'MerchantId' => env('CIELO_MERCHANT_ID'),
                    'MerchantKey' => env('CIELO_MERCHANT_KEY'),
                ]
            ]);

            //Monta a requisição
            $req = [
                'MerchantOrderId' => uniqid(),
                'Customer' => [
                    'Name' => $customer->first_name . ' ' . $customer->last_name,
                    'Email' => $customer->email,
                ],
                'Payment' => [
                    'Type' => 'DebitCard',
                    'Amount' => $request->totalValue * 100,
                    'Installments' => 1,
                    'SoftDescription' => $request->name,
                    'ReturnUrl' => 'http://localhost:8000', 
                    'DebitCard' => [
                        'CardNumber' => $request->cardNumber,
                        'Holder' => $request->cardHolder,
                        'ExpirationDate' => $request->expiryDate,
                        'SecurityCode' => $request->cvv,
                        'Brand' => $request->brand
                    ],
                ],

            ];
            try {
                $response = $client->request('POST', '/1/sales', [
                    'json' => $req
                ]);
                
                //Obter o corpo da resposta
                $responseData = json_decode($response->getBody()->getContents(), true);
                $this->getOrder($request, $responseData);
                return $this->createDebitPayment($responseData, $request);
                //return response()->json($responseData);
            } catch (Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } catch (Exception $e) {
            return response()->json($e);
        }

       
    } 
    public function pixPayment(Request $request)
    {
        $access = $this->getAccess();
        $client = $this->getClient();
        $customer = Auth::guard('customer')->user();
        if ($access) {
         
            try{
                $payment = $client->create([
                    "transaction_amount" => (float) $request->totalValue,
                    "description" => $request->description,
                    "installments" => 1,
                    "payer" => [
                        "email" => $customer->email,
                        "first_name" => $customer->first_name ,
                        "last_name" => $customer->last_name,
                        "identification" => [
                            "type" => 'CPF',
                            "number" => $request->cpfPayer
                        ]
                    ],
                    "payment_method_id" => 'pix'
                ]);
                
                
                return $this->createPayment($payment, $request);
            }
            catch(Exception $e){
                return response()->json($e->getMessage());
            }
            
        }

        /* Cielo integration 
        $customer = Auth::guard('customer')->user();
        try{
            $req = [
                'MerchantOrderId' => uniqid(),
                'Customer' => [
                    'Name' => $customer->first_name . ' ' . $customer->last_name,
                    //'Email' => $customer->email,
                    "Identity" => str_replace(['.', '-'], '', $request->cpfPayer),
                    "IdentityType" => "CPF"
                ],
                'Payment' => [
                    'Type' => $request->paymentType,
                    'Amount' => $request->totalValue * 100,
                ]
            ];
            
            try{
                $response = $client->request('POST', '/1/sales', [
                    'json' => $req
                ]);
              
                return response()->json($response);
            }
            catch(Exception $e){
                return response(['Error' => $e->getMessage()], 500);
            }
        }
        catch(Exception $e){
            return response()->json($e);
        }
        
    }
    /*public function createPayment($preference, $request)
    {   
        $payer = Auth::guard('customer')->user();

        try {
            $client = $this->getClient();
            if ($client) {

                $payment = Payment::create([
                    "transaction_id" => $preference->id,
                    "status" => "pendent",
                    "quantity" => $request->quantity,
                    "transaction_amount" => $request->totalValue,
                    "description" => $request->description,
                    "payment_method_id" => $request->paymentType,
                    "payer" => $payer->email,
                    "user_id" => $payer->id
                ]);
                
                return response()->json($preference);
            }


            return response()->json($preference);
        } catch (MPApiException $e) {
            // Log do erro
            return response()->json(['error' => 'Erro ao processar o pagamento', $e->getMessage()], 500);
        } catch (Exception $e) {
            // Log do erro
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /*public function createDebitPayment($responseData, $request)
    {
        $payer = Auth::guard('customer')->user();

        try {
            $payment = Payment::create([
                "transaction_id" => $responseData['MerchantOrderId'],
                "status" => "pendent",
                "quantity" => $request->quantity,
                "transaction_amount" => $request->totalValue,
                "description" => $request->description,
                "payment_method_id" => $request->paymentType,
                "payer" => $payer->email,
                "user_id" => $payer->id
            ]);
            $this->getMelhorEnvio($request);
            return response()->json($responseData);
        } catch (Exception $e) {
            return response()->json($e);
        }
    } 
    
    public function getCieloClient()
    {
        $cieloClient = new Client([
            'base_uri' => 'https://apisandbox.cieloecommerce.cielo.com.br',
            'headers' => [
                'Content-Type' => 'application/json',
                'MerchantId' => env('CIELO_MERCHANT_ID'),
                'MerchantKey' => env('CIELO_MERCHANT_KEY'),
            ]
        ]);

        return $cieloClient;
    }
    public function getAccess()
    {
        $access_token = getenv('MERCADOPAGO_ACCESS_TOKEN');
        $public_key = getenv('MERCADOPAGO_PUBLICKEY');
        $init = MercadoPagoConfig::setAccessToken($access_token);
        return response()->json($init);
    }
    public function getClient()
    {
        $client = new PaymentClient();

        return $client;
    }
    public function getOrder($request, $responseData){
        
        $order = new OrderController();
        return $order->create($request, $responseData);
    }
    public function getMelhorEnvio($request){
        
        $melhorEnvio = new MelhorEnvioController();
        return $melhorEnvio->createCart($request);
    }
    */
   
}
