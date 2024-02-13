<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'campaigns' => 'array',
    ];

    protected $appends = ['subtotal', 'orderStatus'];

    protected $hidden = ['uuid', 'notes', 'customer_id', 'campaigns', 'orderStatus', 'total', 'created_at', 'updated_at'];

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }

    public function getSubtotalAttribute(): float
    {
        return (float)number_format(($this->price * $this->quantity) - $this->discount, 2);
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class, 'uuid', 'uuid');
    }

    public function getOrderStatusAttribute($value): string
    {
        return $this->items()->where('status', 'pending')->count() === 0 ? 'completed' : 'pending';
    }
}
