<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\ProcessDatasController;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    //
    public function payment(Request $request){
        $data = $request->input();
        $processDatas = $this->getProcessDataClass($request);
        return response()->json($processDatas);
        
    }

    public function getProcessDataClass($request){
        $process = new ProcessDatasController();
        return $process->getDatas($request);
    }
    
}
