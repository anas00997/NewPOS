<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;

class OrderProduct extends Model
{
    use HasFactory, BelongsToCompany;

    protected $guarded = [];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    protected $appends = ['discounted_price'];
    public function getDiscountedPriceAttribute()
    {
            return number_format(($this->total / $this->quantity), 2); 
    }
}
