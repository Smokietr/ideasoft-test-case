<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{

    public ?string $load = null;


    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function load(string $load): self
    {
        $this->load = $load;

        return $this;
    }

    public function toArray(Request $request): array
    {

        $response = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'revenue' => formattedPrice($this->revenue),
        ];

        if ($this->load) {
            $response[$this->load] = OrderResource::collection(collect($this->orders)->groupBy('uuid'));
        }

        return $response;
    }
}
