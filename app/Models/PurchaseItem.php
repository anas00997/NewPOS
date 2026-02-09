<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;

class PurchaseItem extends Model
{
    use HasFactory, BelongsToCompany;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'branch_id',
        'company_id',
        'purchase_price',
        'price',
        'discount_value',
        'discount_type',
        'quantity',
    ];
    protected $appends = ['name', 'stock'];
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    // Accessor for Product Name
    public function getNameAttribute()
    {
        return $this->product ? $this->product->name : null;
    }

    // Accessor for Current Stock Quantity
    public function getStockAttribute()
    {
        return $this->product ? $this->product->quantity : null;
    }
}
