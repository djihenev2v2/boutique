<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id',
        'customer_name', 'customer_phone', 'customer_email',
        'wilaya_id', 'address',
        'subtotal', 'shipping_cost', 'discount', 'total',
        'promo_code_id', 'status', 'payment_method',
        'notes', 'confirmed_at', 'shipped_at', 'delivered_at', 'cancelled_at',
    ];

    protected $casts = [
        'subtotal'      => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount'      => 'decimal:2',
        'total'         => 'decimal:2',
        'confirmed_at'  => 'datetime',
        'shipped_at'    => 'datetime',
        'delivered_at'  => 'datetime',
        'cancelled_at'  => 'datetime',
    ];

    /** Status label/badge helpers */
    public const STATUSES = [
        'pending'   => ['label' => 'En attente',  'color' => 'amber'],
        'confirmed' => ['label' => 'Confirmée',   'color' => 'blue'],
        'shipped'   => ['label' => 'Expédiée',    'color' => 'violet'],
        'delivered' => ['label' => 'Livrée',      'color' => 'emerald'],
        'cancelled' => ['label' => 'Annulée',     'color' => 'red'],
    ];

    public const PAYMENT_METHODS = [
        'cod'       => 'Paiement à la livraison',
        'baridimob' => 'BaridiMob',
        'cib'       => 'CIB',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (Order $order) {
            if (empty($order->order_number)) {
                $order->order_number = 'CMD-' . strtoupper(Str::random(8));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wilaya(): BelongsTo
    {
        return $this->belongsTo(Wilaya::class);
    }

    public function promoCode(): BelongsTo
    {
        return $this->belongsTo(PromoCode::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shipment(): HasOne
    {
        return $this->hasOne(Shipment::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('changed_at');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status]['label'] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUSES[$this->status]['color'] ?? 'slate';
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return self::PAYMENT_METHODS[$this->payment_method] ?? $this->payment_method;
    }
}
