<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Controllers\Controller;
use Exception;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    //
    private $customer;

    public function __construct(Customer $customer){
        $this->customer = $customer;
    }

    public function index(){
        dd('teste index');
    }
    public function store(Request $request){
        $data = $request;
        try{
            $insert = Customer::create(
                [
                'first_name' => $data->first_name,
                'last_name' => $data->last_name,
                'email' => $data->email,
                'password' => $data->password,
                
                ]
            );
                $response = 'Usuario cadastrado com sucesso';
                return response()->json($response);
        }
        catch(Exception $e){
            $response = $e;
            
            return throw new Exception($response);
        }
    }
    public function logout(Request $request){
        Auth::logout();

        $request->session('customer')->invalidate();

        $request->session('customer')->regenerateToken();

        return redirect('/');
    }
     
    

}
