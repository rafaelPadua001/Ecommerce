<?php

namespace App\Http\Controllers\MelhorEnvio;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
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
           
            $calculator->postalCode(env('CEP_CLIENT'), '73050140');
            
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
}
