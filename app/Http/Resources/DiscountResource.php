<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'discountReason' => $this['campaign'],
            'discountAmount' => formattedPrice($this['discount']),
            'subtotal' => formattedPrice($this['total']),
        ];
    }
}
