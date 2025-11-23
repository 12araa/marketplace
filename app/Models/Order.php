<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Import Model lain untuk relasi
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "total_price",
        "status",
        "shipping_name",
        "shipping_address",
        "shipping_phone",
    ];

    /**
     * Relasi: Satu Order dimiliki oleh satu User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Satu Order memiliki banyak OrderItem
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }
    public function scopeShipped($query)
    {
        return $query->where('status', 'Shipped');
    }
}
