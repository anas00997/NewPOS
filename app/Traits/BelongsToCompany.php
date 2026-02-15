<?php

namespace App\Traits;

use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToCompany
{
    public static function bootBelongsToCompany()
    {
        static::addGlobalScope('company', function (Builder $builder) {
            if ($builder->getModel() instanceof \App\Models\User) {
                if (auth()->guard()->hasUser()) {
                    $user = auth()->user();
                    if ($user && $user->company_id) {
                        $builder->where(
                            $builder->getModel()->getTable() . '.company_id',
                            $user->company_id
                        );
                    }
                }
            } else {
                if (auth()->check()) {
                    $user = auth()->user();
                    if ($user && $user->company_id) {
                        $builder->where(
                            $builder->getModel()->getTable() . '.company_id',
                            $user->company_id
                        );
                    }
                }
            }
        });

        // Automatically save company_id when creating models
        static::creating(
            function ($model) {
                if (auth()->check()) {
                    $user = auth()->user();
                    if ($user && $user->company_id) {
                        $model->company_id = $user->company_id;
                    }
                }
            }
        );
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
