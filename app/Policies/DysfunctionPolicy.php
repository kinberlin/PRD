<?php

namespace App\Policies;

use App\Models\Dysfunction;
use App\Models\Users;
use Illuminate\Auth\Access\Response;

class DysfunctionPolicy
{
    /**
     * Determine whether the Dysfunction can be Identified again.
     */
    public function DysIdentify(Users $users, Dysfunction $dysfunction): bool
    {
        return $dysfunction->status == 2;
    }

    /**
     * Determine whether the Dysfunction can be planified.
     */
    public function DysPlanify(Users $users, Dysfunction $dysfunction): bool
    {
        return $dysfunction->status == 4;
    }
    /**
     * Determine whether the Dysfunction can be Evaluated.
     */
    public function DysEvaluate(Users $users, Dysfunction $dysfunction): bool
    {
        return $dysfunction->status == 5;
    }
}
