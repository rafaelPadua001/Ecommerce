<?php

namespace App\Http\Controllers\Coupons;

use App\Http\Controllers\Controller;
use App\Services\CouponService\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Coupon;
use Exception;

class CouponsController extends Controller
{
    //
    protected $couponService;
    public function __construct(CouponService $couponService){
        $this->couponService = $couponService;
    }
    public function index(){
        try{
            $coupons = $this->couponService->getAll();
            return response()->json($coupons);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function create(Request $request){
        try{
            $user = Auth::user();
            $coupon = $this->couponService->store($request);
            
            return response()->json($coupon);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function update(Request $request, $id){
        try{
            
            $updated = $this->couponService->updateCoupon($request, $id);
            
           return response()->json($updated);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function destroy($id){
        try{
            $coupon = $this->couponService->delete($id);
            return response()->json($coupon);
        }
        catch(Exception $e){
            return response()->json($e);
        }        
    }
}
