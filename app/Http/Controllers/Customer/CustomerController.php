<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Http\Controllers\Controller;
use Exception;



class CustomerController extends Controller
{
    protected $customer;
    
    public function __construct(Customer $customer){
        $this->customer = $customer;
    }

    public function index(){
        try{
            $customer = Auth::guard('customer')->user();
            if(!$customer){
                return response()->json($customer);
            }
           
            return response()->json($customer);
        }catch(Exception $e){
            return response()->json($e);
        }
       
    }
    public function store(Request $request){
        $data = $request;
        try{
            $insert = Customer::create(
                $request->all()
            );
                $response = 'Usuario cadastrado com sucesso';
                return response()->json($response);
        }
        catch(Exception $e){
            $response = $e;
            
            return throw new Exception($response);
        }
    }
    public function update(Request $request, $id){
        try{
            $customer = Customer::findOrFail($id);
            
            if($request->has('password')){
                //criptografa a nova sernha
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
