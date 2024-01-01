<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
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
        $data = $request;
        try{
            $insert = $this->customerService->create($request);
            return response()->json($insert);
        }
        catch(Exception $e){
            return throw new Exception($e);
        }
    }
    public function update(Request $request, $id){
        try{
            $customer = Customer::findOrFail($id);
            
            if($request->has('password')){
                //criptografa a nova senha
                $request->merge(['password' => bcrypt($request->password)]);

            }
            $update = $customer->update($request->all());
            return response()->json($update);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }   
    public function logout(Request $request){
       try{
            Auth::guard('customer')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();
            $response = true;
            return response()->json($request);
        //redirect('/login');
       }
       catch(Exception $e){
        return response()->json($e);
       }
        
    }
     
    

}
