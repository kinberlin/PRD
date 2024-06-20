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
    public function DysCanIdentify(Users $users, Dysfunction $dysfunction): bool
    {
        return ($dysfunction->status == 1 || $dysfunction->status == 2 || $dysfunction->status == 4);
    }

    /**
     * Determine whether the Dysfunction can be planified.
     */
    public function DysCanPlanify(Users $users, Dysfunction $dysfunction): bool
    {
        return $dysfunction->status == 3;
    }
    /**
     * Determine whether the Dysfunction can be Evaluated.
     */
    public function DysCanEvaluate(Users $users, Dysfunction $dysfunction): bool
    {
        return $dysfunction->status == 4;
    }
        /**
     * Determine whether the Dysfunction can be Evaluated.
     */
    public function DysInEvaluation(Users $users, Dysfunction $dysfunction): bool
    {
        return $dysfunction->status == 5;
    }
    public function DysRunning(Users $users, Dysfunction $dysfunction): bool
    {
        return $dysfunction->status != 3 && $dysfunction->status != 6;
    }
}
