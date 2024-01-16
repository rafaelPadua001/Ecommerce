<?php

namespace App\Http\Controllers\Coupons\CouponCustomer;

use App\Models\CuponCustomer;
use App\Http\Controllers\Controller;
use App\Services\CouponService\CouponCustomer\CouponCustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class CouponCustomerController extends Controller
{
    //
    protected $couponCustomerService;
    public function __construct(CouponCustomerService $couponCustomerService){
        $this->couponCustomerService = $couponCustomerService;
       
    }
    public function getAll(){
        $customer = Auth::guard('customer')->user();
     
        $coupon = CuponCustomer::where('user_id', '=', $customer->id)->get();
       
        return response()->json($coupon);
    }
    public function getCoupon($id, Request $request){
        try{
            
            $coupon = $this->couponCustomerService->rescueCoupon($id, $request);
       
            return response()->json($coupon);
        }
        catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }
    public function remove($id){
       try{
        $coupon = CuponCustomer::findOrFail($id)->delete();
       
        return response()->json($coupon);
       }
       catch(Exception $e){
            return response()->json($e->getMessage());
       }
    }
}
