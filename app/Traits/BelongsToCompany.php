<?php

namespace App\Traits;

use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToCompany
{
    public static function bootBelongsToCompany()
    {
        // Add global scope to filter by company_id automatically
        static::addGlobalScope('company', function (Builder $builder) {
            // Prevent infinite recursion when the User model itself is being retrieved for authentication
            if ($builder->getModel() instanceof \App\Models\User) {
                // For User model, only apply scope if user is already loaded/authenticated
                // This prevents the scope from triggering a user retrieval which triggers the scope...
                if (auth()->guard()->hasUser()) {
                     $builder->where($builder->getModel()->getTable() . '.company_id', auth()->user()->company_id);
                }
            } else {
                // For other models, normal check (which might trigger retrieval if needed)
                if (auth()->check()) {
                    $builder->where($builder->getModel()->getTable() . '.company_id', auth()->user()->company_id);
                }
            }
        });

        // Automatically save company_id when creating models
        static::creating(function ($model) {
            // Check for recursion here too implicitly via check(), but hasUser check above saves us if retrieval happens
            if (auth()->check()) {
                $model->company_id = auth()->user()->company_id;
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
