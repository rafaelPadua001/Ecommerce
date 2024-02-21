<?php

namespace App\Services\CustomerService;

use App\Models\Customer;
use App\Models\ProfileImage;
use App\Services\CouponService\CouponCustomer\CouponCustomerService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class CustomerService
{
    protected $customer;
    protected $couponCustomerService;

    public function __construct(
        Customer $customer,
        CouponCustomerService $couponCustomerService
    )
    {
        $this->customer = $customer;
        $this->couponCustomerService = $couponCustomerService;
    }
    public function getCustomer()
    {
        try {
            $customer = Auth::guard('customer')->user();
          
            $customerProfile = ProfileImage::where('customer_id', $customer->id)
                ->join('customers', 'customers.id', '=', 'profile_images.customer_id')
                ->select(
                    'customers.id',
                    'customers.first_name',
                    'customers.last_name',
                    'customers.email',
                    'profile_images.name',
                    'profile_images.extension',
                )
                ->first();

            if (!$customerProfile) {
              
                return $customer;
            }
           return $customerProfile;
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function create(Request $request)
    {
        try {
            $insert = Customer::create($request->all());
            $coupon = $this->couponCustomerService->rescueWelcome($insert);
            return response()->json($insert);
        } catch (Exception $e) {
            return throw new Exception($e);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $customer = Customer::findOrFail($id);

            if ($request->has('password')) {
                //criptografa a nova senha
                $request->merge(['password' => bcrypt($request->password)]);
            }
            $update = $customer->update($request->all());
            return $request;
        } catch (Exception $e) {
            return $e;
        }
    }
    public function clearSession(Request $request)
    {
        try {
            Auth::guard('customer')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();
            $response = true;
            return response()->json($request);
            //redirect('/login');
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function destroyAccount($id){
        try{
            $delete = $this->customer->findOrFail($id)->delete();
           
            return $delete;
        }
        catch(Exception $e){
            return $e;
        }
    }
}
