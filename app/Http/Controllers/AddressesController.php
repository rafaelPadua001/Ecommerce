<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class AddressesController extends Controller
{
    //
    public function index(){
        $customer = Auth::guard('customer')->user();
        $address = Address::where('user_id', $customer->id)->first();
        return response()->json($address);
    }
    public function create(Request $request){
        
        try{
            $customer = Auth::guard('customer')->user();
           
            $address = Address::create([
                    'endereco' => $request->address,
                    'complemento' => $request->complemento,
                    'bairro' => $request->bairro,
                    'cidade' => $request->city,
                    'estado' => $request->state,
                    'cep' => $request->postal_code,
                    'UF' => $request->uf,
                    'pais' => $request->country,
                    'telefone' => $request->phone,
                    'number' => $request->number,
                    'user_id' => $customer->id
                ]);
                
                return response()->json($address);             
            
           
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function update(Request $request, $id){
        try{
            $address = Address::where('id', $id)->update($request->all());
            return response()->json($address);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
}
