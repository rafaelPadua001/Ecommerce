<?php
namespace App\Services\PaymentService;

use Exception;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use App\Http\Controllers\Orders\OrderController;
use App\Http\Controllers\MelhorEnvio\MelhorEnvioController;

class PaymentService {
    protected $payment;
    public function __construct(Payment $payment){
        $this->payment = $payment;
    }
    public function paymentType(Request $request){
        $paymentType = $request->paymentType;
        if ($paymentType == 'credit') {
         //   return $this->creditPayment($request);
        } else if ($paymentType == 'debit') {
            return $this->debitPayment($request);
        } else {
           // return $this->pixPayment($request);
            // throw new Exception('Ainda não criamos isso !');
        }
    }
    public function debitPayment(Request $request)
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
    public function createDebitPayment($responseData, $request)
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
    public function getOrder($request, $responseData){
        
        $order = new OrderController();
        return $order->create($request, $responseData);
    }
    public function getMelhorEnvio($request){
        
        $melhorEnvio = new MelhorEnvioController();
        return $melhorEnvio->createCart($request);
    }
}