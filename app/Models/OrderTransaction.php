<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;

class OrderTransaction extends Model
{
    use HasFactory, BelongsToCompany;
    protected $table = 'order_transactions';
    protected $fillable = ['amount', 'order_id', 'user_id', 'customer_id', 'paid_by', 'branch_id', 'company_id'];
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
