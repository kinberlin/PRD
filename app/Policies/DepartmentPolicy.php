<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\Users;
use Illuminate\Auth\Access\Response;

class DepartmentPolicy
{
    /**
     * Determine whether the Department is deletable.
     * By ensuring it dont have any dependency data, it is said deletable.
     */
    public function canDepDelete(Users $users, Department $department): bool
    {
        if(is_null($department)){
            return false;
        }
         return $department->users()->exists() ? false : true;
    }
}
