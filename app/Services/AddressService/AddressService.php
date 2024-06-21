<?php
namespace App\Services\AddressService;

use App\Models\Address;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressService{
    protected $address;
    
    public function __construct(Address $address){
        $this->address = $address;
    }
    public function getAddress(){
        try{
            $customer = Auth::guard('customer')->user();
            $address = $this->address->where('user_id', $customer->id)->first();
          
            return $address;
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function getAuthenticated(){
        $customer = Auth::guard('customer')->user();
        return $customer;
    }
    public function store(Request $request){
        try{
            $customer = $this->getAuthenticated();
           
            $address = $this->address->create([
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
            $address = $this->address->where('id', $id)->update($request->all());
           
            return $request;
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function delete($id){
        try{
            $address = $this->address->findOrFail($id)->delete();
            return response()->json($address);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    

}