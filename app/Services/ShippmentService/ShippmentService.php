<?php

namespace App\Services\ShippmentService;

use App\Models\Shippment;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;

class ShippmentService
{
    protected $shippment;
    public function __construct(Shippment $shippment)
    {
        $this->shippment = $shippment;
    }
    public function getAll()
    {
        try {
            $shippments = DB::table('shippments')
                ->join('products', 'shippments.product_id', '=', 'products.id')
                ->join('customers', 'shippments.user_id', '=', 'customers.id')
                ->select(
                    'shippments.*',
                    'products.name as product_name',
                    'customers.first_name',
                    'customers.last_name'
                )
                ->get();


            // $this->shippment->all();
            return $shippments;
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
    public function getAuthenticated()
    {
        $customer = Auth::guard('customer')->user();
        return $customer;
    }
    public function store($delivery_item)
    {

        try {
            $customer = $this->getAuthenticated();
            $itens = $this->createCart($delivery_item);

            $data = [
                'name' => $delivery_item['shippment']['company']['name'],
                'company' => $delivery_item['shippment']['company']['name'], //$delivery_item['delivery'][0]['company']['name'],
                'price' => $delivery_item['shippment']['price'],
                'deadline' => $delivery_item['shippment']['delivery_time'] ? false : 0,
                'product_id' => $delivery_item['cartItem'][0]['product_id'],
                'itens' => $itens,
                'quantity' => $delivery_item['quantity'],
                'user_id' => $customer->id,
                'cart_id' => $delivery_item['cartItem'][0]['cart_id'],
                'cart_item_id' => $delivery_item['cartItem'][0]['cart_item_id'],
                'delivery_id' =>  $delivery_item['shippment']['company_id'],
                'company_id' => $delivery_item['shippment']['company']['id'],
                'company_id_agency' => $delivery_item['shippment']['company_id'] //['id']// Use ['id'] se for o campo correto
            ];

            $store_shippment = $this->shippment->create($data);

            return $store_shippment;
        } catch (Exception $e) {
            return $e;
        }
    }
    public function createCart($delivery_item)
    {
        $items = json_encode($delivery_item['cartItem']);
        return $items;
    }
    public function update($id, $request)
    {
        try {
            $shippment_update = $this->shippment->findOrFail($id);

            $update = $shippment_update->update($request->all());


            return $request->all();
        } catch (Exception $e) {
            return  $e;
        }

        return $request;
    }
    public function destroy($id)
    {
        $shippment_delete = $this->shippment->findOrFail($id)->delete();
        return $shippment_delete;
    }
}
