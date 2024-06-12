<?php

namespace App\Services\CartService;

use App\Models\Cart;
use App\Services\CartService\CartItemService;
use Exception;
use Illuminate\Support\Facades\DB;

class CartService
{
    protected $carts;
    protected $cartItemService;

    public function __construct(
        Cart $carts,
        CartItemService $cartItemService
    ) {
        $this->carts = $carts;
        $this->cartItemService = $cartItemService;
    }
    public function getCarts($userId)
    {
        try{
            $cart = $this->carts->where('carts.user_id', $userId)
            ->join('cart_items', 'cart_items.cart_id', '=', 'carts.id')
            ->leftJoin('shippments', 'cart_items.id', '=', 'shippments.cart_item_id')
            ->leftJoin('products', 'shippments.product_id', '=', 'products.id')
             ->select(
                'carts.id as cart_id',
                //'cart_items.quantity as cart_item_quantity',
                'cart_items.id as cart_item_id',
                'cart_items.colors as cart_item_colors',
                'cart_items.size as cart_item_size',
                'cart_items.price as cart_item_price',
                'cart_items.is_active as cart_item_status',
                'cart_items.total_price as total_price',
                'cart_items.delivery_price as delivery_price',
                'shippments.name as shippment_name',
                'shippments.company',
                'shippments.price as shippment_price',
                'shippments.quantity as shippment_quantity',
                'shippments.company_id as company_id',
                'shippments.company_id_agency as company_agency_id',
                'products.name',
                'products.description as product_description',
                'products.price as product_price',
                'products.images',
                'products.weight',
                'products.height',
                'products.width',
                'products.length',
            )
            ->get();

        return $cart;
        }
        catch(Exception $e){
            return $e;
        }
        
    }
    public function addItem($request, $userId)
    {
        $cart = $this->carts->where('user_id', '=', $userId)->latest()->first();

        if (!$cart) {
            $create = $this->carts->create(['user_id' => $userId]);
            //return $create;
        }

        $cart_item = $this->cartItemService->addCartItem($userId, $request);

        //$stock_quantity = $this->reduceItemStock($request->quantity, $cart_item);
        return $cart_item;
    }
    //
    //public function reduceItemStock($quantity, $cart){
    //    $product = new ProductStockController();
    //    return $product->reduceQuantity($quantity, $cart);
    //}
}
