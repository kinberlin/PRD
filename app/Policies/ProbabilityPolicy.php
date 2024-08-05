<?php

namespace App\Policies;

use App\Models\Probability;
use App\Models\Users;

class ProbabilityPolicy
{
    /**
     * Determine whether the Probability is deletable.
     * By ensuring it dont have any dependency data, it is said deletable.
     */
    public function canProbDelete(Users $users, Probability $probability): bool
    {
        if (is_null($probability)) {
            return false;
        }
        return $probability->dysfunctions()->exists() ? false : true;
    }

    /**
     * Determine whether the Probability is visible on dysfunction identification form.
     */
    public function isProbabilityVisible(Users $users, Probability $probability): bool
    {
        if (is_null($probability)) {
            return false;
        }
        return $probability->visible;
    }
}
