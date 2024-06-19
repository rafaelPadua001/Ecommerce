<?php

namespace App\Services\PaymentService\Partials;

use Exception;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\CartService\CartItemService;

class PixPayment
{

    protected $carItemService;

    public function __construct(
        CartItemService $cartItemService,
    ) {
        $this->carItemService = $cartItemService;
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
    public function alterStatusItem($ids)
    {

        return $this->carItemService->updateActive($ids);
    }
}
