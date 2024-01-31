<?php
namespace App\Services\CarrouselService;

use App\Models\Carrousel;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Storage;

class CarrouselService {
    protected $carrousel;
    public function  __construct(Carrousel $carrousel){
        $this->carrousel = $carrousel;
    }
    public function getCarrousel($id){
        try{
            $carrousel = $this->carrousel->where('store_id', '=', $id)->get();
            return $carrousel;
        }
        catch(Exception $e){
            return $e;
        }
    }
    public function store($request, $id){
        
        try{
            $user = Auth::user();
            // dd($images['name']);
                $create = $this->carrousel->create([
                    'user_id' => $user->id,
                    'store_id' => $id,
                    'images' => json_encode($request),
                ]);
                return $create;
        }
        catch(Exception $e){
            
            return $e->getMessage();
        }
    }
    public function update($request, $storeId){
        try{
          
            $user = Auth::user();
          
            $images = $this->carrousel->where('store_id', '=', $storeId['store_id'])->get();
           
            $removeImages = $this->remove($images);
            foreach ($images as $i => $image) {
                // Certifique-se de que existe um banner correspondente
                $nameImage = 'banner' . ($i + 2);
               
                if (isset($request[$nameImage]['name'])) {
                    // Atualize a imagem com o nome do banner correspondente
                    $update = $this->carrousel->where('id', '=', $image['id'])->update([
                        'user_id' => $user->id,
                        'store_id' => $storeId['store_id'],
                        'images' => $request[$nameImage]['name'],
                    ]);
                
                }
            }
                
            return $update;
        }
        catch(Exception $e){
            return $e;
        }

    }
    public function remove($images){
        foreach($images as $img){
            $removeImages = Storage::delete('/public/Carrousel/'.$img->images);
            return $removeImages;
        }
        
    }
}