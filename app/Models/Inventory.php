<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'price',
        'stock',
    ];

    /**
     * Get the purchase details for the inventory.
     */
    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    /**
     * Get the sale details for the inventory.
     */
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
}
