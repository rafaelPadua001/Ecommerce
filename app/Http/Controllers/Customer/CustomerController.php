<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Exception;
use App\Services\CustomerService\CustomerService;



class CustomerController extends Controller
{
    protected $customerService;
    
    public function __construct(CustomerService $customerService){
        $this->customerService = $customerService;
    }

    public function index(){
        try{
            $customer = $this->customerService->getCustomer();
            return response()->json($customer);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function store(Request $request){
        try{
            $insert = $this->customerService->create($request);
            return response()->json($insert);
        }
        catch(Exception $e){
            return response()->json($e->getMessage());
        }
    }
    public function update(Request $request, $id){
        try{
           $update = $this->customerService->update($request, $id);
           return response()->json($update);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }   
    public function logout(Request $request){
       try{
            $clearSession = $this->customerService->clearSession($request);
            return response()->json($clearSession);
        //redirect('/login');
       }
       catch(Exception $e){
        return response()->json($e);
       }
        
    }
    public function destroy($id){
        try{
            $delete = $this->customerService->destroyAccount($id);
            return response()->json($delete);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
     
    

}
