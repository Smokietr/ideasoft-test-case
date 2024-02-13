<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Customers extends Model
{
    use HasFactory, SoftDeletes, QueryCacheable;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $appends = ['revenue'];

    protected static bool $flushCacheOnUpdate = true;

    public int $cacheFor = 3600;

    public function getRevenueAttribute()
    {
        return $this->orders->sum('total');
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class, 'customer_id');
    }
}
