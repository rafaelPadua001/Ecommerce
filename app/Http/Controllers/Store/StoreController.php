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
    public function update($id, Request $request){
        try{
            $update = $this->storeService->updateStore($id, $request);
           
            return response()->json($update);
        }
        catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }
    public function styleCreate(Request $request){
        try{
            $style = $this->storeService->styleStore($request);
           
            return response()->json($style);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function delete($id){
        try{
            $delete = $this->storeService->destroy($id);
            return response()->json($delete);
        }
        catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

   
}
