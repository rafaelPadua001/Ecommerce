<?php
namespace App\Services\PaymentService;

use Exception;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use App\Http\Controllers\Orders\OrderController;
use App\Http\Controllers\MelhorEnvio\MelhorEnvioController;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use App\Services\CartService\CartItemService;
use App\Services\CouponService\CouponCustomer\CouponCustomerService;
use App\Services\StockService\StockService;

class PaymentService {
    protected $payment;
    protected $carItemService;
    protected $couponCustomerService;
    protected $stockService;
    public function __construct(
        Payment $payment,
        CartItemService $cartItemService,
        CouponCustomerService $couponCustomerService,
        StockService $stockService
    ){
        $this->payment = $payment;
        $this->carItemService = $cartItemService;
        $this->couponCustomerService = $couponCustomerService;
        $this->stockService = $stockService;
    }
    public function paymentType(Request $request){
        $paymentType = $request->paymentType;
        if ($paymentType == 'credit') {
            return $this->creditPayment($request);
        } else if ($paymentType == 'debit') {
            return $this->debitPayment($request);
        } else {
            return $this->pixPayment($request);
            // throw new Exception('Ainda não criamos isso !');
        }
    }
    public function debitPayment(Request $request)
    {
       $customer = Auth::guard('customer')->user();
        
        try {
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
            $createOrder = $this->getOrder($request, $responseData);
            
            if($createOrder){
                $melhorEnvio = $this->getMelhorEnvio($request);
            }
            
           
            $itemId = $request['id'];
            $this->alterStatusItem($itemId);
            if($request->coupon_id >= 1){
                $this->removeCoupon($request->coupon_id);
            }
            if($request->quantity >= 1){
                $this->reduceStock($request);
            }
            
            $notification = $this->notification($responseData);
           // dd($notification);
            return response()->json($responseData);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function creditPayment(Request $request)
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
             return $this->createCreditPayment($preference, $request);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function createCreditPayment($preference, $request)
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
                
                
                return $this->createCreditPayment($payment, $request);
            }
            catch(Exception $e){
                return response()->json($e->getMessage());
            }
            
        }
    }
    public function notification($response){
        try{
            $merchantKey = env('CIELO_MERCHANT_KEY');
            $url =  $response['Payment']['Links'][0]['Href'];
            
            $headers = [
                'Authorization:' . $merchantKey,
                'Content-Type : application/json',   
            ];

            $ch = curl_init($url);
           
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $return = curl_exec($ch);
            if(!$return){
                $error = curl_error($ch);
            }

            curl_close($ch);

            return $return;
            
        }
        catch(Exception $e){
            return $e;
        }
       // dd($response['Payment']['Links'][0]['Href']);
       $link = $response['Payment']['Links'][0]['Href'];
       header("Location: $link");
       exit();
        //redirect($response['Payment']['Links'][0]['Href']);
    }
    public function alterStatusItem($id){
       
        return $this->carItemService->updateActive($id);
        
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
    public function removeCoupon($id){
        $coupon = $this->couponCustomerService->remove($id);
        return $coupon;
    }
    public function reduceStock($request){
        $quantity = $request->quantity;
        $cart = $request;
        $reduceStock = $this->stockService->reduceItemStock($quantity, $cart);
        return response()->json($reduceStock);
    }
}