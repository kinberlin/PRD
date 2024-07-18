<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\Users;
use Illuminate\Auth\Access\Response;

class DepartmentPolicy
{
    /**
     * Determine whether the Department is deletable.
     */
    public function canDelete(Users $users, Department $department): bool
    {
        if(is_null($department)){
            return false;
        }
         return $department->users()->exists();
    }
}
