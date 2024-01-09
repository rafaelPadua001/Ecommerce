<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Services\PaymentService\PaymentService;

class PaymentController extends Controller
{
    //
    protected $paymentService;
    public function __construct(PaymentService $paymentService){
        $this->paymentService = $paymentService;
    }
    public function payment(Request $request){
        $data = $request->input();
        $processDatas = $this->paymentService->paymentType($request);
     
        return response()->json($processDatas);
        
    }

   
    
}
