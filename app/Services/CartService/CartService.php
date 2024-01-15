<?php

namespace App\Services\CartService;

use App\Models\Cart;
use App\Services\CartService\CartItemService;

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
        $cart = $this->carts->where('carts.user_id', $userId)
            ->join('cart_items', 'cart_items.cart_id', '=', 'carts.id')
            ->leftJoin('products', 'cart_items.product_id', '=', 'products.id')
            ->select(
                'carts.id as cart_id',
                'cart_items.*',
                'products.name',
                'products.price',
                'products.images',

            )
            ->get();

        return $cart;
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
