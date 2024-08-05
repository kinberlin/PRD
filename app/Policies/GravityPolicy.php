<?php

namespace App\Policies;

use App\Models\Users;
use App\Models\Gravity;
use Illuminate\Auth\Access\Response;

class GravityPolicy
{
    /**
     * Determine whether the Gravity is deletable.
     * By ensuring it dont have any dependency data, it is said deletable.
     */
    public function canGravityDelete(Users $users, Gravity $gravity): bool
    {
        if(is_null($gravity)){
            return false;
        }
         return $gravity->dysfunctions()->exists() ? false : true;
    }

    /**
     * Determine whether the Gravity is visible on dysfunction identification form.
     */
    public function isGravityVisible(Users $users, Gravity $gravity): bool
    {
        if(is_null($gravity)){
            return false;
        }
         return $gravity->visible;
    }
}
