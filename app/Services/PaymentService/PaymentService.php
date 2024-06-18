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
use App\Services\ShippmentService\ShippmentService;
use App\Services\OrderService\OrderService;
use App\Services\PaymentService\Partials\CreditPayment;
use App\Services\PaymentService\Partials\DebitPayment;
use App\Services\PaymentService\Partials\CreditPaymentPayment;
use Illuminate\Support\Str;

class PaymentService
{
    protected $payment;
    protected $carItemService;
    protected $couponCustomerService;
    protected $stockService;
    protected $shippmentService;
    protected $orderService;
    protected $debitPaymentService;
    protected $creditPaymentService;
    public function __construct(
        Payment $payment,
        CartItemService $cartItemService,
        CouponCustomerService $couponCustomerService,
        StockService $stockService,
        ShippmentService $shippmentService,
        OrderService $orderService,
        DebitPayment $debitPaymentService,
        CreditPayment $creditPaymentService,
    ) {
        $this->payment = $payment;
        $this->carItemService = $cartItemService;
        $this->couponCustomerService = $couponCustomerService;
        $this->stockService = $stockService;
        $this->shippmentService = $shippmentService;
        $this->orderService = $orderService;
        $this->debitPaymentService = $debitPaymentService;
        $this->creditPaymentService = $creditPaymentService;
    }
    public function paymentType(Request $request)
    {
        $paymentType = $request->paymentType;
        if ($paymentType == 'credit') {
            $creditPayment = $this->creditPaymentService->creditPayment($request);
            return $creditPayment;
            //return $this->creditPayment($request);
        } else if ($paymentType == 'DebitCard') {
            $debitPayment = $this->debitPaymentService->debitPayment($request);
            return $debitPayment;
            // return $this->debitPayment($request);
        } else {
            return $this->pixPayment($request);
            // throw new Exception('Ainda não criamos isso !');
        }
    }
    public function pixPayment(Request $request)
    {

        $access = $this->getAccess();
        $client = $this->getClient();
        $customer = Auth::guard('customer')->user();
        if ($access) {

            try {
                $payment = $client->create([
                    "transaction_amount" => (float) $request->totalValue,
                    "description" => $request->description,
                    "installments" => 1,
                    "payer" => [
                        "email" => $customer->email,
                        "first_name" => $customer->first_name,
                        "last_name" => $customer->last_name,
                        "identification" => [
                            "type" => 'CPF',
                            "number" => $request->cpfPayer
                        ]
                    ],
                    "payment_method_id" => 'pix'
                ]);


                //return $this->createCreditPayment($payment, $request);
            } catch (Exception $e) {
                return response()->json($e->getMessage());
            }
        }
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
    public function alterStatusItem($ids)
    {

        return $this->carItemService->updateActive($ids);
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
}
