<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'lab_id',
        'name',
        'contact_person',
        'email',
        'phone',
        'alternate_phone',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'tax_id',
        'payment_terms',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class, 'preferred_supplier_id');
    }

    public function scopeForCurrentLab($query)
    {
        return $query->where('lab_id', currentLabId());
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
