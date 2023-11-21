<?php

namespace App\Http\Controllers\Coupons;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Coupon;
use Exception;

class CouponsController extends Controller
{
    //
    public function index(){
        try{
            $coupons = Coupon::all();
            return response()->json($coupons);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function create(Request $request){
        try{
            $user = Auth::user();
            $coupon = Coupon::create([
                'code' => $request->code_coupon,
                'discount_percentage' => $request->value_coupon,
                'user_id' => $user->id 
            ]);
            
            return response()->json($coupon);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function update(Request $request, $id){
        try{
            $user = Auth::user();
            $coupon = Coupon::where('id', $id)->update($request->all());
            $updated = Coupon::where('id', $id)->first();
            
           return response()->json($updated);
        }
        catch(Exception $e){
            return response()->json($e);
        }
    }
    public function destroy($id){
        try{
            $coupon = Coupon::where('id', $id)->delete();
            return response()->json($coupon);
        }
        catch(Exception $e){
            return response()->json($e->getMessage());
        }        
    }
}
