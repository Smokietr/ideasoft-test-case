<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $response = [];

        foreach ($this->resource as $k => $order) {
            $response[] = [
                'product' => $order->product_id,
                'quantity' => $order->quantity,
                'unitPrice' => formattedPrice($order->price),
                'discount' => formattedPrice($order->discount),
                'subtotal' => formattedPrice($order->subtotal),
            ];
        }

        $response['total'] = formattedPrice($this->resource->sum('subtotal'));

        return $response;
    }
}
