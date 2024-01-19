<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Services\StoreService\StoreService;
use Illuminate\Http\Request;
use Exception;  

class StoreController extends Controller
{
    //
    protected $storeService;
    public function __construct(StoreService $storeService){
        $this->storeService = $storeService;
    }
    public function index(){
        try{
            $index = $this->storeService->getStore();
            return response()->json($index);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function create(Request $request){
        try{
            $create = $this->storeService->store($request);
            
            return response()->json($create);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }

   
}
