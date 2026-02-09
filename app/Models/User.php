<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use App\Models\Branch;
use App\Traits\BelongsToCompany;

/**
 * @method bool can(string $ability, mixed $arguments = [])
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Authorizable, BelongsToCompany;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'google_id',
        'profile_image',
        'is_google_registered',
        'is_suspended',
        'branch_id',
        'company_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public $appends = ['pro_pic'];

    public function getProPicAttribute()
    {
        return imageRecover($this->profile_image);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
