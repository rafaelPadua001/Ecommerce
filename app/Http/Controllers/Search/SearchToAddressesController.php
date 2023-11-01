<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use Exception;
use App\Models\SearchToAddress;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class SearchToAddressesController extends Controller
{
    //
    public function search(Request $request){
        
       try{
            $client = $this->getClient();
            
            $url = $client->get("https://viacep.com.br/ws/" . $request->uf . '/' . $request->city . '/' . $request->address . '/json/');
        
            $response = json_decode($url->getBody, true)['cep'];
         
            $this->saveAddress($response, $request);
            return response()->json($response);
        }   
        catch(Exception $e){
            return response()->json($e);
        }
        

    }
    public function save(Request $request){
   //    dd($request);
        try{
            $customer = Auth::guard('customer')->user();
            $address = SearchToAddress::create([
                'postal_code' => $request->postal_code,
                'type_postal_code'=> 'CEP',
                'uf' => $request->uf,
                'cidade' => $request->city,
                'bairro' => $request->bairro,
                'endereco' => $request->address,
                'complemento' => $request->complemento,
                'codigoIBGE' => $request->code_ibge,
                'user_id'=> $customer->id
            ]);

            return response()->json($address);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function getClient(){
        $client = new Client();
        return response()->json($client);
    }
}
