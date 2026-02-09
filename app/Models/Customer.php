<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToCompany;

class Customer extends Model
{
    use HasFactory, BelongsToCompany;

    protected $fillable = ['name', 'phone', 'email_address', 'dob', 'address', 'company_id'];
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
