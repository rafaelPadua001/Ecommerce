<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\ProcessDatasController;
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
      //  $processDatas = $this->getProcessDataClass($request);
        return response()->json($processDatas);
        
    }

    public function getProcessDataClass($request){
        $process = new ProcessDatasController();
        return $process->getDatas($request);
    }
    
}
