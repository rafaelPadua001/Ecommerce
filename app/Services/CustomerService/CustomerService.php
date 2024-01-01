<?php

namespace App\Services\CustomerService;

use App\Models\Customer;
use App\Models\ProfileImage;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CustomerService{
    protected $customer;
    public function __construct(Customer $customer){
        $this->customer = $customer;
    }
    public function getCustomer(){
        try{
            $customer = Auth::guard('customer')->user();
            $customerProfile = ProfileImage::where('customer_id', $customer->id)
                ->join('customers', 'customers.id', '=', 'profile_images.customer_id')
                ->select(
                    'customers.first_name',
                    'customers.last_name',
                    'customers.email',
                    'profile_images.name',
                    'profile_images.extension')
                ->first();
           
            if(!$customerProfile){
                return $customer;
            }
           
            return $customerProfile;
        }catch(Exception $e){
            return response()->json($e);
        }
    }
    public function create(Request $request){
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
}