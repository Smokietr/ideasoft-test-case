<?php

namespace App\Http\Repository;

use App\Models\Product;

class DiscountRepository
{
    protected array $campaigns = [
        '10_PERCENT_OVER_1000' => [
            'type' => 'total',
            'min' => 1000,
            'discount' => 0.1,
        ],
        'BUY_6_GET_1_CATEGORY_2' => [
            'type' => 'free',
            'category' => 2,
            'min' => 6,
            'free' => 1,
        ],
        '20_PERCENT_OVER_2_ITEMS_CATEGORY_1' => [
            'type' => 'quantity',
            'category' => 1,
            'min' => 2,
            'discount' => 0.2,
        ],
    ];

    protected mixed $items;

    protected $subtotal, $totalDiscount;

    public function __construct($items = [])
    {
        $this->items = $items;
    }

    public function calculateDiscount(): array
    {
        $this->subtotal = $this->calculateSubTotal($this->items);
        return $this->checkCampaign();
    }

    public function setProducts($items): static
    {
        foreach ($items as $item) {
            $product = Product::find($item['id']);

            $this->items[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $item['quantity'],
                'category' => $product->category,
                'stock' => $product->stock,
            ];
        }

        return $this;
    }


    public function calculateSubTotal($items): float
    {
        return collect($items)->map(function ($item) {
            return $item['price'] * $item['quantity'];
        })->sum();
    }

    public function getMinPrice()
    {
        return collect($this->items)->min('price');
    }

    public function getItemPrice($item)
    {
        return $this->items[$item]['price'];
    }

    public function getPriceForCategory($category)
    {
        return collect($this->items)->where('category', $category)->min('price');
    }

    public function checkCampaign(): array
    {
        $campaigns = [];

        foreach ($this->campaigns as $campaign => $value) {
            if($value['type'] === 'total' && $value['min'] < $this->subtotal) {
                $campaigns[] = [
                    'campaign' => $campaign,
                    'discount' => $this->subtotal * $value['discount'],
                    'total' => $this->subtotal
                ];
            }

            if ($value['type'] === 'free') {
                $sum = collect($this->items)->where('category', $value['category']);
                if ($sum->sum('quantity') >= $value['min']) {
                    $campaigns[] = [
                        'campaign' => $campaign,
                        'discount' => $this->getPriceForCategory($value['category']),
                        'total' => $sum->map(function ($item) {
                            return $item['price'] * $item['quantity'];
                        })->sum()
                    ];
                }
            }

            if ($value['type'] === 'quantity') {
                $sum = collect($this->items)->where('category', $value['category']);
                if ($sum->sum('quantity') >= $value['min']) {
                    $campaigns[] = [
                        'campaign' => $campaign,
                        'discount' => $sum->min('price') * $value['discount'],
                        'total' => $sum->map(function ($item) {
                            return $item['price'] * $item['quantity'];
                        })->sum()
                    ];
                }
            }

            $this->totalDiscount = collect($campaigns)->sum('discount');
        }

        return $campaigns;
    }

    public function getDiscountedTotal()
    {
        return formattedPrice($this->subtotal);
    }

    public function getNotDiscountedTotal()
    {
        return formattedPrice($this->subtotal - $this->totalDiscount);
    }

    public function getTotalDiscount()
    {
        return formattedPrice($this->totalDiscount);
    }
}
