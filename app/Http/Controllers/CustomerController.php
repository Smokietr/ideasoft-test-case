<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Http\Resources\OrderResource;
use App\Models\Customers;
use App\Http\Requests\StoreCustomersRequest;
use App\Http\Requests\UpdateCustomersRequest;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return CustomerResource::collection(Customers::paginate(20)->withQueryString());
    }

    public function show(Customers $customers)
    {
        //return OrderResource::collection($customers->orders->groupBy('uuid'));
        return (new CustomerResource($customers))->load('orders');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomersRequest $request): CustomerResource|\Illuminate\Http\JsonResponse
    {
        $create = Customers::create($request->only(['name', 'email', 'address']));

        if($create) {
            return new CustomerResource($create);
        }

        return response()->json(['message' => 'Customer could not be created.'], 500);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomersRequest $request, Customers $customers): CustomerResource|\Illuminate\Http\JsonResponse
    {
        $update = $customers->update($request->only(['name', 'address']));

        if ($update) {
            return new CustomerResource($customers);
        }

        return response()->json(['message' => 'Customer could not be updated.'], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customers $customers): \Illuminate\Http\JsonResponse
    {
        $delete = $customers->delete();

        if ($delete) {
            return response()->json(['message' => 'Customer has been deleted.']);
        }

        return response()->json(['message' => 'Customer could not be deleted.'], 500);
    }
}
