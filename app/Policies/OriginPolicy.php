<?php

namespace App\Policies;

use App\Models\Origin;
use App\Models\Users;
use Illuminate\Auth\Access\Response;

class OriginPolicy
{
    /**
     * Determine whether the Origin is deletable.
     * By ensuring it dont have any dependency data, it is said deletable.
     */
    public function canOriginDelete(Users $users, Origin $origin): bool
    {
        if(is_null($origin)){
            return false;
        }
         return $origin->dysfunctions()->exists() ? false : true;
    }
}
