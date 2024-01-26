<?php
namespace App\Services\CardService;

use App\Models\Card;
use Illuminate\Support\Facades\Auth;
use Exception;

class CardService{
    protected $card;
    public function __construct(Card $card){
        $this->card = $card;
    }
    public function getCard($id){
        try{
            $card = $this->card->where('store_id', '=', $id)->get();
            return $card;
        }
        catch(Exception $e){
            return $e;
        }
    }
    public function store($request, $id){
        try{
            $user = Auth::user();
            $create = $this->card->updateOrCreate([
                'chip_color' => json_encode($request),
                'user_id' => $user->id,
                'store_id' => $id
            ]);

            return $create;
        }
        catch(Exception $e){
            return $e;
        }
       
    }
    public function update($request, $storeId){
        try{
            $user = Auth::user();
            $update = $this->card->where('store_id', "=", $storeId)->update([
                'chip_color' => json_encode($request->chip_color),
                'user_id' => $user->id,
                'store_id' => $storeId
            ]);

            return $update;
        }
        catch(Exception $e){
            return $e;
        }
    }
}