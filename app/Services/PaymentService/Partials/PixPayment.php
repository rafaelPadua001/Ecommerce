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
    public function getAuthenticated(){
        $customer = Auth::guard('customer')->user();
        return $customer;
    }
    public function pixPayment(Request $request)
    {
        try{
            $access = $this->getAccess();
            $client = $this->getClient();
            $customer = $this->getAuthenticated();
            
            if($access){
                $payment = $client->create([
                    "transaction_amount" => (float) $request->totalValue,
                    "description" => 'Ecommerce Teste',
                    "installments" => 1,
                    "payer" => [
                        "email" => $customer->email,
                        "first_name" => $customer->first_name,
                        "last_name" => $customer->last_name,
                        "identification" => [
                            "type" => 'CPF',
                            "number" => $request->document
                        ]
                    ],
                    "payment_method_id" => 'pix'
                ]);
            }
                
                return $payment;
        }
        catch (Exception $e) {
            return response()->json($e->getMessage());
        }
       
    }
    public function alterStatusItem($ids)
    {

        return $this->carItemService->updateActive($ids);
    }
}
