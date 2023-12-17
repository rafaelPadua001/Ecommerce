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
    public function update(Request $request, $id){
      try{
         $shippment_update = $this->shippmentService->update($id, $request);
         if(!$shippment_update){
            return response()->json(['error' => 'Update error. Please try again later.'], 500);
         }
       
         return response()->json($request->all());
      }
      catch(Exception $e){
         return response()->json($e);
      }

      return response()->json($request);
    }
    public function remove($id){
      try{
         $shippment_delete = $this->shippmentService->destroy($id);
         return response()->json($shippment_delete);
      }
      catch(Exception $e){
         return response()->json($e);
      }
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
