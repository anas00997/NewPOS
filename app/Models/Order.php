<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use App\Traits\BelongsToCompany;

use function PHPSTORM_META\map;

class Order extends Model
{
    use HasFactory, BelongsToCompany;

    protected $guarded = [];
    protected $appends = ['total_item'];
    public function products()
    {
        return $this->hasMany(OrderProduct::class);
    }
    public function transactions()
    {
        return $this->hasMany(OrderTransaction::class);
    }
    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function getTotalItemAttribute()
    {
        return $this->products()->sum('quantity');
    }

    public function setCreatedAtAttribute($value)
    {
        $cutoffConfig = Config::get('business_day.cutoff_time');
        // Always use today for cutoff time
        $cutoffTime = Carbon::today()->setTimeFromTimeString($cutoffConfig);
        $currentTime = Carbon::now();

        if ($currentTime->gt($cutoffTime)) {
            $this->attributes['created_at'] = $currentTime->addDay()->format('Y-m-d H:i:s');
        } else {
            $this->attributes['created_at'] = $value;
        }
    }

}
