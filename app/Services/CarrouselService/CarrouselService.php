<?php
namespace App\Services\CarrouselService;

use App\Models\Carrousel;
use Illuminate\Support\Facades\Auth;
use Exception;

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
            
            foreach($request as $images){
               // dd($images['name']);
                $create = $this->carrousel->updateOrCreate([
                    'user_id' => $user->id,
                    'store_id' => $id,
                    'images' => json_encode($images['name']),
                ]);
            }
                
                return $create;
        }
        catch(Exception $e){
            
            return $e->getMessage();
        }
    }
}