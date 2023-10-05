<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'purchase_id',
        'inventory_id',
        'quantity',
        'price',
    ];

    /**
     * Get the purchase that owns the purchase detail.
     */ 
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Get the inventory that owns the purchase detail.
     */
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
