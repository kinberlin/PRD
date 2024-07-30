<?php

namespace App\Policies;

use App\Models\Users;
use App\Models\gravity;
use Illuminate\Auth\Access\Response;

class GravityPolicy
{
    /**
     * Determine whether the Gravity is deletable.
     * By ensuring it dont have any dependency data, it is said deletable.
     */
    public function canDepDelete(Users $users, Gravity $gravity): bool
    {
        if(is_null($gravity)){
            return false;
        }
         return $gravity->dysfunctions()->exists() ? false : true;
    }
}
