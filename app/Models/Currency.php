<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToCompany;

class Currency extends Model
{
    use HasFactory, BelongsToCompany;
    protected $fillable = ['name', 'code', 'symbol', 'active', 'company_id'];
    protected $casts = ['active' => 'boolean'];
}
