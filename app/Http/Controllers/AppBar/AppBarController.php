<?php

namespace App\Http\Controllers\AppBar;

use App\Http\Controllers\Controller;
use App\Services\AppBarService\AppBarService;
use Illuminate\Http\Request;
use Exception;

class AppBarController extends Controller
{
    //
    protected $appBarService;
    public function __construct(AppBarService $appBarService){
        $this->appBarService = $appBarService;
    }
    public function create(Request $request, $id){
        try{
            $create = $this->appBarService->store($request, $id);
            return $create;
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
}
