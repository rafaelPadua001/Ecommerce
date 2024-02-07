<?php

namespace App\Http\Controllers\Card;

use App\Http\Controllers\Controller;
use App\Services\CardService\CardService;
use Illuminate\Http\Request;

class CardController extends Controller
{
    //
    protected $cardService;
    public function __construct(CardService $cardService){
        $this->cardService = $cardService;
    }
    public function index(){
        $card = $this->cardService->getCardAttributes();
        return response()->json($card);
    }
    
}
