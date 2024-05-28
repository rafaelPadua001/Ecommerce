<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Services\CartService\CartItemService;
use App\Services\CartService\CartService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $cartService;

    protected $cartItemService;
    public function __construct(
        CartService $cartService,
        CartItemService $cartItemService,
    ) {

        $this->cartService = $cartService;
        $this->cartItemService = $cartItemService;
    }
    public function index()
    {
        try {
            $customer = Auth::guard('customer')->user();
            if (!$customer) {

                return false;
            }
            $userId = $customer->id;
            $carts = $this->cartService->getCarts($userId);

            return response()->json($carts);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function addItem(Request $request)
    {
        try {
            $customer = Auth::guard('customer')->user();
            $userId = $customer->id;
            $cartItem = $this->cartService->addItem($request, $userId);
            return response()->json($cartItem);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
}
