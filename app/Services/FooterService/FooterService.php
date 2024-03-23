<?php

namespace App\Services\FooterService;

use App\Models\Footer;
use Illuminate\Support\Facades\Auth;
use Exception;

class FooterService{
    protected $footer;

    public function __construct(Footer $footer){
        $this->footer = $footer;
    }
    public function store($request, $id){
        try{
            
            $userId = Auth::user();
          
            $create = Footer::create([
                'user_id' => $userId->id,
                'store_id' => $id,
                'links' => json_encode($request['links']),
                'icons' => json_encode($request['icons']),
                'text' => $request['text'],
                'color' => $request['color']
            ]);

            return $create;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function update($id, $request){
        dd();
    }
}