<?php

namespace App\Services\FooterService;

use App\Models\Footer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Exception;

class FooterService{
    protected $footer;

    public function __construct(Footer $footer){
        $this->footer = $footer;
    }
    public function getFooter(){
        try{
            $footer = Footer::all();
            return $footer;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
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
    public function update($request, $id){
      
        try{
            $user = Auth::user();
            //$update = Footer::findOrFail($request['footer_id']);
            
            $update = Footer::findOrFail($request['footer_id'])->update([ 
                'user_id' => $user->id,
                'store_id' => $id,
                'links' => json_encode($request['links']),
                'icons' => json_encode($request['icons']),
                'text' => $request['text'],
                'color' => $request['color']
            ]);
           
            return $request;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function delete($id){
        
        try{
            $delete = Footer::where('store_id', '=', $id)->delete();
            return true;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
}