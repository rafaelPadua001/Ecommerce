<?php
namespace App\Services\PaymentService\Partials;

use Exception;
use App\Models\Payment;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\MelhorEnvio\MelhorEnvioController;
use App\Services\CartService\CartItemService;
use App\Services\CouponService\CouponCustomer\CouponCustomerService;
use App\Services\StockService\StockService;
use App\Services\ShippmentService\ShippmentService;
use App\Services\OrderService\OrderService;
use Illuminate\Support\Str;

class CreditPayment {
    protected $payment;
    protected $carItemService;
    protected $couponCustomerService;
    protected $stockService;
    protected $shippmentService;
    protected $orderService;
    public function __construct(
        Payment $payment,
        CartItemService $cartItemService,
        CouponCustomerService $couponCustomerService,
        StockService $stockService,
        ShippmentService $shippmentService,
        OrderService $orderService
    ) {
        $this->payment = $payment;
        $this->carItemService = $cartItemService;
        $this->couponCustomerService = $couponCustomerService;
        $this->stockService = $stockService;
        $this->shippmentService = $shippmentService;
        $this->orderService = $orderService;
    }
    public function getApiClient()
    {
        $api_client = new Client([
            'base_uri' => 'https://apisandbox.cieloecommerce.cielo.com.br',
            'headers' => [
                'Content-Type' => 'application/json',
                'MerchantId' => env('CIELO_MERCHANT_ID'),
                'MerchantKey' => env('CIELO_MERCHANT_KEY'),
                'User-Agent' => 'GuzzleHttp/7',
            ],
            'verify' => false
        ]);
        return $api_client;
    }
    public function getCustomer()
    {
        $customer = Auth::guard('customer')->user();
        return $customer;
    }
    public function createRequest($request)
    {
        $customer = $this->getCustomer();
        
        $merchantOrderId = uniqid();

        $req = [
            'MerchantOrderId' => $merchantOrderId,
            'Customer' => [
                'Name' => $customer->first_name . ' ' . $customer->last_name,
                'Identity' => $request->document,
                'IdentityType' => "CPF",
                'Email' => $customer->email,
                
            ],
            'Payment' => $this->createPayment($request),
        ];

        return $req;
    }
    public function createPayment($request)
    {
        return [
            'Currency' => 'BRL',
            'Country' => 'BRA',
            'ServiceTaxAmount' => 0,
            'Installments' => (int) $request->installments,
            'Interest' => "ByIssuer",
            'Capture' => true,
            'Authenticate' => false,
            'Recurrent' => false,
            'SoftDescription' => 'Teste Ecommerce',
            //'ReturnUrl' => 'http://localhost:8000',
            'CreditCard' => [
                'CardNumber' => $request->cardNumber,
                'Holder' => $request->cardHolder,
                'ExpirationDate' => $request->expiryMonth . '/' . $request->expiryYear,
                'SecurityCode' => $request->cvv,
                'SaveCard' => false,
                'Brand' => $request->cardBrand,
                'CardOnFile' => [
                    'Usage' => 'Used',
                    'Reason' => 'Unscheduled'
                ]
            ],
            'IsCryptoCurrencyNegotiation' => false,
            'Type' => 'CreditCard',
            'Amount' => (int) ($request->totalValue) * 100,
        ];
    }
    

    public function creditPayment(Request $request)
    {
        try {
            $customer = $this->getCustomer();
            $client = $this->getApiClient();
          
            $req = $this->createRequest($request);
            
            $response = $client->request('POST', '/1/sales', [
                'json' => $req,
                'debug' => false
            ]);
            
            $responseData = json_decode($response->getBody()->getContents(), true);
           
            return $this->createCreditPayment($responseData, $request);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function createCreditPayment($responseData, $request)
    {
        try {
            $payer = $this->getCustomer();
            $cartItems = $request->input('cartItem');
            $itemNames = [];
            foreach ($cartItems as $item) {
                $itemNames[] = $item["name"];
            }
            $itemNameJson = json_encode($itemNames);

            $payment = Payment::create([
                "transaction_id" => $responseData['MerchantOrderId'],
                "status" => "pendent",
                "quantity" => $request->quantity,
                "transaction_amount" => $request->totalValue,
                "products" => $itemNameJson,
                "payment_method_id" => $request->paymentType,
                "payer" => $payer->email,
                "user_id" => $payer->id
            ]);

            $createOrder = $this->getOrder($request, $itemNameJson, $responseData);

            if ($createOrder) {
                $melhorEnvio = $this->getMelhorEnvio($request);

                $createShippment = $this->shippmentService->store($melhorEnvio);
            }


            $itemIds = $this->getItensId($request['cartItem']);

            $alterStatus = $this->alterStatusItem($itemIds);

            if ($request->coupon_id >= 1) {
                $removeCoupon =  $this->removeCoupon($request->coupon_id);
            }

            $reduceItems = $this->reduceQuantityItens($request);

            $capture = $this->captureTransaction($responseData);
            
            //$shippment = $this->storeShippment($request);

           
            return response()->json($capture);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
    public function getItensId($cartItems)
    {
        $cartItemId = [];
        foreach ($cartItems as $item) {
            return $cartItemId[] = [
                'cart_item_ids' => $item['cart_item_id'],
            ];
        }
    }
    public function reduceQuantityItens($request)
    {
        if ($request->quantity >= 1) {
            try {
                $reduceItem = $this->reduceStock($request);


                $responseData = $reduceItem->getData(true);

                if (isset($responseData['error'])) {
                    return response()->json(['message' => $responseData['error']], 400);
                }
                return $responseData;
            } catch (Exception $e) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
        }
    }
    public function getOrder($request, $itemNameJson, $responseData)
    {
        $order = $this->orderService->store($request, $itemNameJson, $responseData); //new OrderController();
        return $order;
    }
    public function getMelhorEnvio($request)
    {
        $melhorEnvio = new MelhorEnvioController();

        return $melhorEnvio->createCart($request);
    }
    public function alterStatusItem($ids)
    {

        return $this->carItemService->updateActive($ids);
    }
    public function captureTransaction($response)
    {
        
        try {
            $merchantId = env('CIELO_MERCHANT_ID');
            $merchantKey = env('CIELO_MERCHANT_KEY');
            $requestId = (string) Str::uuid();
            // dd($merchantId, $merchantKey);
            if (!$merchantId || !$merchantKey) {
                return throw new Exception("MerchantId or MerchantKey is not set in the environment file.");
            }


            $url =  $response['Payment']['Links'][0]['Href'];
           
            $headers = [
                'Content-Type: application/json',
                'MerchantId: ' . $merchantId,
                'MerchantKey: ' . $merchantKey,
                'RequestId: ' . $requestId,
            ];

            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_VERBOSE, true);

            $return = curl_exec($ch);
           
            if ($return == false) {
                $error = curl_error($ch);
                curl_close($ch);
                throw new Exception('Curl error:' . $error);
            }

            curl_close($ch);
          
            return $return;
        } catch (Exception $e) {
            return $e->getMessage();
        }
        // dd($response['Payment']['Links'][0]['Href']);
        $link = $response['Payment']['Links'][0]['Href'];
        header("Location: $link");
        exit();
        //redirect($response['Payment']['Links'][0]['Href']);
    }
    public function removeCoupon($id)
    {
        $coupon = $this->couponCustomerService->remove($id);
        return $coupon;
    }
    public function reduceStock($request)
    {
        $quantity = $request->quantity;
        $cart = $request['cartItem'];
        $reduceStock = $this->stockService->reduceItemStock($quantity, $cart);

        return response()->json($reduceStock);
    }
}