<?php

namespace App\Http\Requests;

use App\Http\Repository\DiscountRepository;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => [
                'required', 'integer', 'exists:customers,id'
            ],
            'items' => [
                'required', 'array', 'min:1'
            ],
            'items.*.id' => [
                'required', 'integer', 'exists:products,id'
            ],
            'items.*.quantity' => [
                'required', 'integer', 'min:1'
            ],
        ];
    }

    /**
     * @throws ValidationException
     */
    protected function passedValidation(): void
    {
        foreach ($this->items as $k => $item) {
            $product = Product::find($item['id']);
            if ($product->stock < $item['quantity']) {
                throw ValidationException::withMessages([sprintf('items.%s.quantity', $k) => 'The product ' . $product->name . ' does not have enough stock']);
            }

            $products[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $item['quantity'],
                'category' => $product->category,
                'stock' => $product->stock,
            ];
        }

        $invoiceID = \Str::uuid();

        foreach ($products as $product) {

            $order[] = [
                'uuid' => $invoiceID,
                'customer_id' => $this->customer_id,
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'total' => $product['price'] * $product['quantity'],
                'discount' => @number_format(array_sum(array_column((new DiscountRepository($products))->calculateDiscount(), 'discount')) / count($products), 2) ?? 0,
                'campaigns' => json_encode(array_column((new DiscountRepository($products))->calculateDiscount(), 'campaign')),
                'status' => 'pending',
            ];
        }

        $this->merge([
            'orders' => $order,
        ]);
    }
}
