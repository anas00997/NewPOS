<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;

class Purchase extends Model
{
    use HasFactory, BelongsToCompany;

    protected $fillable = [
        'supplier_id',
        'user_id',
        'branch_id',
        'company_id',
        'sub_total',
        'tax',
        'discount_value',
        'discount_type',
        'shipping',
        'grand_total',
        'status',
        'date',
    ];
    protected $table = 'purchases';
    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
