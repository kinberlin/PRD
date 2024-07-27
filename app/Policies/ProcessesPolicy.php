<?php

namespace App\Policies;

use App\Models\Processes;
use App\Models\Users;
use Illuminate\Auth\Access\Response;

class ProcessesPolicy
{
    /**
     * Determine whether the Process is deletable.
     * By ensuring it doesn't have any dependency data, it is said deletable.
     */
    public function canProcessDelete(Users $users, Processes $processes): bool
    {
        if(is_null($processes)){
            return false;
        }
         return ($processes->dysfunctions()->count() > 0 ? false : true) && ($processes->tasks()->exists() ? false : true) && ($processes->authorisationPilote()->exists() ? false : true);
    }
}
