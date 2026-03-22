@component('mail::message')
# Inventory Alert

@if($alertType === 'low_stock')
## ⚠️ Low Stock Alert

The following item is running low on stock:

**Item:** {{ $item->name }}  
**SKU:** {{ $item->sku ?? 'N/A' }}  
**Current Stock:** {{ $item->current_stock }} {{ $item->unit }}  
**Minimum Stock:** {{ $item->minimum_stock }} {{ $item->unit }}

Please consider reordering soon to avoid stockout.

@elseif($alertType === 'out_of_stock')
## 🚨 Out of Stock Alert

The following item is **OUT OF STOCK**:

**Item:** {{ $item->name }}  
**SKU:** {{ $item->sku ?? 'N/A' }}  
**Current Stock:** 0 {{ $item->unit }}

**⚠️ ACTION REQUIRED:** Please reorder immediately to avoid service disruption.

@elseif($alertType === 'expiring_soon')
## ⏰ Expiring Soon Alert

The following item is expiring soon:

**Item:** {{ $item->name }}  
**SKU:** {{ $item->sku ?? 'N/A' }}  
**Current Stock:** {{ $item->current_stock }} {{ $item->unit }}  
**Expiry Date:** {{ $item->expiry_date->format('M d, Y') }}  
**Days Until Expiry:** {{ now()->diffInDays($item->expiry_date, false) }} days

Please use this item first or consider returning it if possible.

@elseif($alertType === 'expired')
## ❌ Expired Item Alert

The following item has **EXPIRED**:

**Item:** {{ $item->name }}  
**SKU:** {{ $item->sku ?? 'N/A' }}  
**Current Stock:** {{ $item->current_stock }} {{ $item->unit }}  
**Expiry Date:** {{ $item->expiry_date->format('M d, Y') }}

**⚠️ ACTION REQUIRED:** Please remove this item from inventory immediately.

@endif

@component('mail::button', ['url' => route('inventory.items.show', $item)])
View Item Details
@endcomponent

Thanks,<br>
{{ config('app.name') }} - Inventory Management System
@endcomponent
