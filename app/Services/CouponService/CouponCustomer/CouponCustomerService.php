<?php
namespace App\Services\CouponService\CouponCustomer;

use App\Models\CuponCustomer;
use App\Services\CouponService\CouponService;
use Exception;
use Illuminate\Support\Facades\Auth;

class CouponCustomerService {
    protected $couponCustomer;
    protected $couponService;
    public function __construct(
        CuponCustomer $couponCustomer,
        CouponService $couponService
    ){
        $this->couponCustomer = $couponCustomer;
        $this->couponService = $couponService;
    }
    public function rescueWelcome($customer){
        try{
            $coupon = $this->couponService->getWelcome();
           
            $register_coupon = $this->couponCustomer->create([
                'user_id' => $customer->id,
                'coupon_id' => $coupon->id,
                'coupon_name' => $coupon->code,
                'discount_percentage' => $coupon->discount_percentage,
                'init_date' => $coupon->init_date,
                'end_date' => $coupon->end_date,
                'is_used' => false
            ]);
          
            return response()->json($register_coupon);
            
        }
        catch(Exception $e){
            return $e;
        }
    }
    
}