<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Import Model lain untuk relasi
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        "order_id",
        "product_id",
        "quantity",
        "price",
    ];

    /**
     * Relasi: Satu OrderItem dimiliki oleh satu Order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relasi: Satu OrderItem merujuk ke satu Product
     */
    public function product(): BelongsTo
    {
        // Kita pakai 'set null' di migration, jadi relasi ini
        // mungkin null. Kita bisa tambahkan 'withDefault()'
        // jika produknya sudah dihapus
        return $this->belongsTo(Product::class)->withDefault([
            'name' => 'Produk Dihapus'
        ]);
    }
}
