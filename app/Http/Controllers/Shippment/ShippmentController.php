<?php

namespace App\Http\Controllers\Shippment;

use Exception;
use Illuminate\Support\Facades\Auth;
use App\Services\ShippmentService\ShippmentService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShippmentController extends Controller
{
    //
    protected $shippmentService;
    public function __construct(ShippmentService $shippmentService){
        $this->shippmentService = $shippmentService;
    }
    public function index(){
      $shippments = $this->shippmentService->getAll();
      return response()->json($shippments);
    }
   // public static function create(Request $request){
   //    
   //     $customer = Auth::guard('customer')->user();
   //     if(!$customer){
   //         throw new Exception('Not logged user');
   //     }
   //    
   //     $shippment_store = $this->shippmentService->store($request->delivery);
//
   //     return response()->json($shippment_store);
//
   // }
}
