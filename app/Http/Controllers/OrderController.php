<?php

namespace App\Http\Controllers;

use App\Http\Repository\DiscountRepository;
use App\Http\Requests\DiscountShowRequest;
use App\Http\Resources\DiscountResource;
use App\Http\Resources\OrderListResource;
use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return OrderListResource::collection(Order::with('items')->groupBy('uuid')->paginate(20)->withQueryString());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request): \Illuminate\Http\JsonResponse
    {
        $create = Order::insert($request->orders);

        if ($create) {
            return response()->json(['message' => 'Order created'], 201);
        }

        return response()->json(['message' => 'Order not created'], 500);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order): \Illuminate\Http\JsonResponse
    {
        $update = $order->update($request->validated());

        if ($update) {
            return response()->json(['message' => 'Order updated'], 200);
        }

        return response()->json(['message' => 'Order not updated'], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order): \Illuminate\Http\JsonResponse
    {
        $delete = $order->delete();

        if ($delete) {
            return response()->json(['message' => 'Order deleted'], 200);
        }

        return response()->json(['message' => 'Order not deleted'], 500);
    }

    public function discount(DiscountShowRequest $request): \Illuminate\Http\JsonResponse
    {

        $repository = (new DiscountRepository())->setProducts($request->items);

        return response()->json(
            [
                'discounts' => DiscountResource::collection($repository->calculateDiscount()),
                'totalDiscount' => $repository->getTotalDiscount(),
                'discountedTotal' => $repository->getDiscountedTotal(),
                'notDiscountedTotal' => $repository->getNotDiscountedTotal(),
            ]
        );
    }
}
