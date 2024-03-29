<?php
namespace App\Services\CouponService\CouponCustomer;

use App\Models\CuponCustomer;
use App\Services\CouponService\CouponService;
use Exception;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

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
    public function rescueCoupon($id, $request){
    
        try{
            
            $register = $this->couponCustomer
                ->where('coupon_id', '=', $request->coupon['id'])
                ->where('user_id', '=', $id)
                ->get();
           
            if($register->isEmpty()){
                $register = $this->couponCustomer->create([
                    'user_id' => $id,
                    'coupon_id' => $request->coupon['id'],
                    'coupon_name' => $request->coupon['code'],
                    'discount_percentage' => $request->coupon['discount_percentage'],
                    'init_date' => $request->coupon['init_date'],
                    'end_date' => $request->coupon['end_date'],
                    'is_used' => $request->coupon['is_used'],
                ]);

                return 'Você recebeu ' . $register->coupon_name;
            }
            
            return 'Você ja possui este cupom';
        }
        catch(Exception $e){
            return $e;
        }
    }
    public function remove($id){
        try{
            $customer = Auth::guard('customer')->user();
            $coupon = $this->couponCustomer
                ->where('user_id', '=', $customer->id)
                ->where('coupon_id', '=', $id)
                ->first();
            $coupon->delete();
            return $coupon;
        }
        catch(Exception $e){
             return response()->json($e->getMessage());
        }
     }
    
}