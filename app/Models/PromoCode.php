<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromoCode extends Model
{
    protected $fillable = [
        'code', 'discount', 'is_percentage', 'is_active',
        'used_count', 'max_uses', 'expires_at',
    ];

    protected $casts = [
        'discount'      => 'decimal:2',
        'is_percentage' => 'boolean',
        'is_active'     => 'boolean',
        'expires_at'    => 'datetime',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'promo_code_id');
    }
}
