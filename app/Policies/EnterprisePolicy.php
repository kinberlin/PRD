<?php

namespace App\Policies;

use App\Models\Enterprise;
use App\Models\Users;
use Illuminate\Auth\Access\Response;

class EnterprisePolicy
{
    /**
     * Determine whether the Enterprise is deletable.
     * By ensuring it dont have any dependency data, it is said deletable.
     */
    public function canEnterpriseDelete(Users $users, Enterprise $enterprise): bool
    {
        if(is_null($enterprise)){
            return false;
        }
         return ($enterprise->dysfunctions()->exists() ? false : true) && ($enterprise->departments()->exists() ? false : true) &&
         ($enterprise->users()->exists() ? false : true) && ($enterprise->sites()->exists() ? false : true) && ($enterprise->authorisationRqs()->exists() ? false : true);
    }

}
