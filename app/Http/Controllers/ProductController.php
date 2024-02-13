<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return ProductResource::collection(Product::paginate(20)->withQueryString());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): \Illuminate\Http\JsonResponse|ProductResource
    {
        $create = Product::create($request->only(['name', 'description', 'price', 'stock', 'category']));

        if($create) {
            return new ProductResource($create);
        }

        return response()->json(['message' => 'Product could not be created.'], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): \Illuminate\Http\JsonResponse|ProductResource
    {
        $update = $product->update($request->only(['name', 'description', 'price', 'stock', 'category']));

        if ($update) {
            return new ProductResource($product);
        }

        return response()->json(['message' => 'Product could not be updated.'], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): \Illuminate\Http\JsonResponse
    {
        $delete = $product->delete();

        if ($delete) {
            return response()->json(['message' => 'Product deleted.']);
        }

        return response()->json(['message' => 'Product could not be deleted.'], 500);
    }
}
