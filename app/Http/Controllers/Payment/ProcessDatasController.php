<?php

namespace App\Http\Controllers\Payment;


use App\Http\Controllers\Controller;
use App\Models\Payment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;


class ProcessDatasController extends Controller
{
    public function getDatas(Request $request)
    {

        $paymentType = $request->paymentType;
        if ($paymentType == 'credit') {
            return $this->creditPayment($request);
        } else if ($paymentType == 'debit') {
            return $this->debitPayment();
        } else {
            throw new Exception('Ainda não criamos isso !');
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
                                "unit_price" => (float) $request->totalValue
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

    public function createPayment($preference, $request)
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
    public function debitPayment()
    {
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
