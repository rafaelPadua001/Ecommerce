<?php

namespace App\Services\CartService;

use App\Models\CartItem;
use App\Models\Cart;
use App\Services\ShippmentService\ShippmentService;
use Exception;
use Illuminate\Support\Facades\Auth;

class CartItemService
{
    protected $cart;
    protected $cartItem;
    protected $shippmentService;

    public function __construct(
        CartItem $cartItem,
        Cart $cart,
        ShippmentService $shippmentService,
    ) {
        $this->cartItem = $cartItem;
        $this->cart = $cart;
        $this->shippmentService = $shippmentService;
    }
    public function addCartItem($id, $product)
    {
        try {
            $cart = $this->cart->where('user_id', '=', $id)->first();
            $customer = Auth::guard('customer')->user();

            $cartItem = $this->cartItem->create([
                'cart_id' => $cart->id,
                'product_id' => $product->product['id'],
                'user_id' => $customer->id,
                'quantity' => $product->quantity,
                'price' => $product->product['price'],
                'colors' => json_encode($product->color),
                'size' => json_encode($product->size),
                'total_price' =>  $product['total_price'],

            ]);

            return $cartItem;
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function createItemCart($id, $customerId, $cart, $cartItem, $product)
    {
        try {
            $delivery_item = [
                'id' => $id,
                'delivery' => $product['delivery'],
                'delivery_name' => $product['delivery_name'],
                'quantity' => $product->quantity,
                'product_id' => $product->product['id'],
                'user_id' => $customerId,
                'cart_id' => $cart->id,
                'cart_item_id' => $cartItem->id,
                'company_id' => $product['delivery']['id'],
                'company_id_agency' => $product['delivery']['company']['id']
            ];
            return $delivery_item;
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function buy($userId)
    {
        try {
            $item = $this->cartItem->where('cart_items.user_id', $userId)
                ->join('customers', 'customers.id', '=', 'cart_items.user_id')
                ->leftJoin('cupon_customers', 'cupon_customers.user_id', '=', 'customers.id')
                ->join('products', 'products.id', '=', 'cart_items.product_id')
                ->leftJoin('addresses', 'addresses.user_id', '=', 'cart_items.user_id')
                ->select(
                    'cart_items.*',
                    'products.name',
                    'products.description',
                    'products.images',
                    'products.height',
                    'products.width',
                    'products.length',
                    'products.weight',
                    'customers.first_name',
                    'customers.last_name',
                    'customers.email',
                    'cupon_customers.coupon_id',
                    'cupon_customers.coupon_name',
                    'cupon_customers.discount_percentage',
                    'cupon_customers.end_date',
                    'cupon_customers.is_used',
                    'addresses.endereco',
                    'addresses.complemento',
                    'addresses.bairro',
                    'addresses.cidade',
                    'addresses.estado',

                )
                ->latest()
                ->first();

            return $item;
        } catch (Exception $e) {
            return response()->json($e);
        }
        return response()->json($item);
    }
    public function checkout($id)
    {
        try {
            $cartItem = CartItem::where('cart_items.id', $id)
                ->join('products', 'cart_items.product_id', '=', 'products.id')
                //->join('addresses', 'cart_items.user_id', '=',  'addresses.user_id')
                ->select('cart_items.*', 'products.*', /*'addresses.*'*/)
                ->first();

            return response()->json($cartItem);
        } catch (Exception $e) {
            return response()->json($e);
        };
    }
    public function updateActive($ids)
    {
        foreach($ids as $id){
            return $cartItemStatus = $this->cartItem->where('cart_items.id', $id)->update([
                'is_active' => false
            ]);
        }
        //return true;
    }
    public function destroy($id)
    {
        try {
            $remove_cartItem = $this->cartItem->findOrFail($id);
            $remove_cartItem->delete();

            return $remove_cartItem;
        } catch (Exception $e) {

            return response()->json($e);
        }
    }
}
