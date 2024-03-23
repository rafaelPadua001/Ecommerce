<?php

namespace App\Http\Controllers\Footer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FooterService\FooterService;
use Exception;

class FooterController extends Controller
{
    //
    protected $footerService;
    public function __construct(FooterService $footerService){
        $this->footerService = $footerService;
    }
    public function create(Request $request, $id){
        try{
            $store = $this->footerService->store($request, $id);
            return response()->json($store);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function update($id, $request){
        dd();
    }
}
