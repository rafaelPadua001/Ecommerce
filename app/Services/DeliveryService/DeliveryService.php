<?php
namespace App\Services\DeliveryService;

use App\Models\Delivery;
use App\Http\Controllers\MelhorEnvio\MelhorEnvioController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DeliveryService {
    protected $delivery;
    public function __construct(Delivery $delivery){
        $this->delivery = $delivery;
    }
    public function getAll(){
        $deliveries = Delivery::all();
        return $deliveries;
    }
    public function store(Request $request){
        // Obtendo a imagem como base64
    $base64Image = $request->input('thumbnail')[0]['src'];

    // Remover o cabeçalho "data:image/jpeg;base64," para obter apenas os dados
    $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);

    // Decodificar a string base64 em dados binários
    $image = base64_decode($base64Image);

    // Gerar um nome único para a imagem
    $imageName = time() . '_' . uniqid() . '.jpg'; // Altere a extensão para a extensão real da imagem
    $tempImagePath = storage_path('app/public/temp/' . $imageName);
    file_put_contents($tempImagePath, $image);

    $upload_image = $this->uploadImage($tempImagePath, $imageName);
    unlink($tempImagePath);
    $activated = filter_var($request->activated, FILTER_VALIDATE_BOOLEAN);
    
        if($upload_image){
            $delivery_store = $this->delivery->create([
                'name' => $request->name,
                'url' => $request->url,
                'thumbnail' => $imageName,
                'activated' => $activated,
                'user_id' => Auth::id()
            ]);
           
        }
        
        return $delivery_store;
    
    }
    public function uploadImage($imagePath, $imageName){
        
        $path = Storage::putFileAs('public/delivery', $imagePath, $imageName);
        return $path;
    }
    public function alterStatus($data){
        $delivery_update = $this->delivery->findOrFail($data['id'])->update([
            'activated' => $data['status']
        ]);
        return $delivery_update;
    }
    public function calculate(Request $request, $shippment_company){
        if($shippment_company){
            $send_calc = $this->getDeliveryController($request);
            return $send_calc;
        }
    }
    public function destroy($id){
        try{
            $this->delivery->findOrFail($id)->delete();
            return true;
        }
        catch(Exception $e){
            return response()->json($e->getMessage());
        }
    }
    public function getDeliveryController($request){
        $company = new MelhorEnvioController();
        return $company->calculate($request); 
    }
}