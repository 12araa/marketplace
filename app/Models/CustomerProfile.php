<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerProfile extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'phone_number',
        'address_line_1',
        'city',
        'province',
        'postal_code',
        'profile_picture_path',
        'bank_account_number',
        'bank_name',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
