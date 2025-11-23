<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorProfile extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'shop_name',
        'shop_description',
        'shop_phone_number',
        'shop_address',
        'shop_city',
        'verification_status',
        'id_card_image_path',
        'bank_account_number',
        'bank_name',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
