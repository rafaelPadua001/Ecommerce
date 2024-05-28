<?php

namespace App\Services\ShippmentService;

use App\Models\Shippment;
use Illuminate\Support\Facades\DB;
use Exception;

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
    public function store($delivery_item)
    {
        // dd($delivery_item, $delivery_item['delivery']['id']);
        // dd('teste');
        $data = [
            'name' => $delivery_item['delivery']['name'],
            'company' => $delivery_item['delivery']['company']['name'], //$delivery_item['delivery'][0]['company']['name'],
            'price' => $delivery_item['delivery']['price'],
            'deadline' => $delivery_item['delivery']['delivery_time'],
            'product_id' => $delivery_item['product_id'],
            'quantity' => $delivery_item['quantity'],
            'user_id' => $delivery_item['user_id'],
            'cart_id' => $delivery_item['cart_id'],
            'cart_item_id' => $delivery_item['cart_item_id'],
            'delivery_id' =>  $delivery_item['delivery']['id'] // Use ['id'] se for o campo correto
        ];

        $store_shippment = $this->shippment->create($data);

        return $store_shippment;
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
