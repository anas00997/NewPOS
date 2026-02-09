<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToCompany;

class Supplier extends Model
{
    use HasFactory, BelongsToCompany;

    protected $fillable = ['name','phone', 'address', 'company_id'];
    protected $table = 'suppliers';
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
