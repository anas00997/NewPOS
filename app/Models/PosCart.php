<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;

class PosCart extends Model
{
    use HasFactory, BelongsToCompany;

    protected $fillable = ['user_id', 'branch_id', 'product_id', 'quantity', 'price', 'company_id'];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
