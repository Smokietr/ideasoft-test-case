<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'customer' => $this->customer_id,
            'items' => $this->items,
            'status' => $this->orderStatus,
            'total' => formattedPrice($this->items->sum('subtotal')),
        ];
    }
}
