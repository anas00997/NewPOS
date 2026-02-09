<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use App\Traits\BelongsToCompany;

class Role extends SpatieRole
{
    use BelongsToCompany;
}
