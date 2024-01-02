<?php
namespace App\Services\CouponService;

use App\Models\Coupon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CouponService{
    protected $coupon;
    public function __construct(Coupon $coupon){
        $this->coupon = $coupon;
    }
    public function getAll(){
        try{
            $coupons = $this->coupon->all();
            return $coupons;
        }
        catch(Exception $e){
            return $e;
        }
    }
    public function store(Request $request){
        try{
            $user = Auth::user();
            $coupon = $this->coupon->create([
                'code' => $request->code_coupon,
                'discount_percentage' => $request->value_coupon,
                'init_date' => $request->init_date,
                'end_date' => $request->end_date,
                'init_hour' => $request->init_hour,
                'end_hour' => $request->end_hour,
                'is_displayed' => $request->display,
                'user_id' => $user->id,
                
            ]);
            
            return $coupon;
        }
        catch(Exception $e){
            return $e;
        }
    }
    public function updateCoupon(Request $request, $id){
        try{
            $user = Auth::user();
            $coupon = $this->coupon->where('id', $id)->update($request->all());
            $updated = $this->coupon->where('id', $id)->first();
            
           return $updated;
        }
        catch(Exception $e){
            return $e;
        }
    }
    public function delete($id){
        try{
            $coupon = $this->coupon->where('id', $id)->delete();
            return $coupon;
        }
        catch(Exception $e){
            return $e->getMessage();
        }   
    }
}