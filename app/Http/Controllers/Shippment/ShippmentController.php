<?php

namespace App\Http\Controllers\Shippment;

use Exception;
use Illuminate\Support\Facades\Auth;
use App\Services\Shippment\ShippmentService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShippmentController extends Controller
{
    //
    protected $shippmentService;
    public function __construct(ShippmentService $shippmentService){
        $this->shippmentService = $shippmentService;
    }
    public static function create(Request $request){
        dd($request->delivery);
        $customer = Auth::guard('customer')->user();
        if(!$customer){
            throw new Exception('Not logged user');
        }
       
        //$this->shippmentsService->store();

    }
}
