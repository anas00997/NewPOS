<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToCompany;

class Unit extends Model
{
    use HasFactory, BelongsToCompany;
    protected $fillable = ['title','short_name', 'company_id'];
}
