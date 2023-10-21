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
            
            $calculator->addProducts(
                new Product(uniqid(), 40, 30, 50, 10.00, 100.0, 1),
                new Product(uniqid(), 5, 1, 10, 0.1, 50.0, 1)
            );
           
            //$package = $this->createPackage($calculator, $request);
            $quotations = $calculator->calculate();
           // dd($quotations);
            return response()->json($quotations);
        } catch (Exception $e) {
            return response()->json($e);
        }

        //    return response()->json($quotations);

    }
    /* public function createPackage($calculator, $request){
        $calculator->addPackages(
            $package = new Package(
                $request->height,
                $request->width,
                $request->length,
                $request->weight,
                $request->price
            )
        );
        return response()->json($package);
    }*/
    public function createShipmentInstance()
    {
        $shipment = new Shipment(env('MELHORENVIO_ACCESS_TOKEN', Environment::SANDBOX));
        return $shipment;
    }
}
