<?php
namespace App\Services\AppBarService;

use App\Models\AppBar;
use Exception;
use Illuminate\Support\Facades\Auth;

class AppBarService{
    protected $appBar;
    public function __construct(AppBar $appBar){
        $this->appBar = $appBar;
    }
    public function store($request, $id){
        try{
            $user = Auth::user();
            $store = $this->appBar->updateOrCreate([
                'user_id' => $user->id,
                'store_id' => $id,
                'colors' => json_encode($request),
                
            ]);
            return $store;
        }
        catch(Exception $e){
            return $e;
        }
    }
}