<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use App\Services\AddressService\AddressService;
class AddressesController extends Controller
{
    protected $addressService;

    public function __construct(AddressService $addressService){
        $this->addressService = $addressService;
    }
    public function index(){
        $address = $this->addressService->getAddress();
        return response()->json($address);
    }
    public function create(Request $request){
        
        try{
                $create = $this->addressService->store($request);
                return response()->json($create);             
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function update(Request $request, $id){
        try{
            $update = $this->addressService->update($request, $id);
            return response()->json($update);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
}
