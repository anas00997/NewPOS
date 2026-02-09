<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToCompany;

class Branch extends Model
{
    use HasFactory, BelongsToCompany;

    protected $fillable = ['name', 'code', 'status', 'company_id'];
}