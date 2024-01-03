<?php

namespace App\Http\Controllers\Coupons\CouponCustomer;

use App\Models\CuponCustomer;
use App\Http\Controllers\Controller;
use App\Services\CouponService\CouponCustomer\CouponCustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponCustomerController extends Controller
{
    //
    protected $couponCustomerService;
    public function __consctuct(CouponCustomerService $couponCustomerService){
        $this->couponCustomerService = $couponCustomerService;
       
    }
    public function getAll(){
        $customer = Auth::guard('customer')->user();
     
        $coupon = CuponCustomer::where('user_id','=',$customer->id)->get();
       
        return response()->json($coupon);
    }
}
