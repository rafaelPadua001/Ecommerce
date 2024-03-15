<?php

namespace App\Http\Controllers\ProductStock;

use App\Http\Controllers\Controller;
use App\Services\StockService\StockService;
use Illuminate\Http\Request;
use Exception;


class ProductStockController extends Controller
{

    // protected $products;
    protected $stockService;
    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index()
    {
        $stocks = $this->stockService->init();
        return response()->json($stocks);
    }
    public function store($name, $quantity, $size, $size_qty,  $colors, $color_qty, $product_id, $user_id)
    {
        try {

            $stock = $this->stockService->create($name, $quantity, $size, $size_qty, $colors, $color_qty, $product_id, $user_id);

            return response()->json($stock);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $stock = $this->stockService->update($request, $id);

            return response()->json($stock);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function reduceQuantity($quantity, $cart)
    {

        try {
            $stock = $this->stockService->reduceItemStock($quantity, $cart);

            return response()->json($stock);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
}
