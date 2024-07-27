<?php

namespace App\Policies;

use App\Models\Dysfunction;
use App\Models\Users;

class DysfunctionPolicy
{
    /**
     * Determine whether the Dysfunction can be Identified again.
     */
    public function DysCanIdentify(Users $users, Dysfunction $dysfunction): bool
    {
        if (!is_null($dysfunction->closed_at)) {
            return false;
        }
        return ($dysfunction->status == 1 || $dysfunction->status == 2 || $dysfunction->status == 4);
    }

    /**
     * Determine whether the Dysfunction can be planified.
     */
    public function DysCanPlanify(Users $users, Dysfunction $dysfunction): bool
    {
        if (!is_null($dysfunction->closed_at)) {
            return false;
        }
        return $dysfunction->status == 3;
    }
    /**
     * Determine whether the Dysfunction can be Evaluated.
     */
    public function DysCanEvaluate(Users $users, Dysfunction $dysfunction): bool
    {
        if (!is_null($dysfunction->closed_at)) {
            return false;
        }
        //if($card->has('products'))
        if ($dysfunction->tasks->count() > 1) {return true;}
        return $dysfunction->status == 4;
    }
    /**
     * Determine whether the Dysfunction can be Evaluated.
     */
    public function DysInEvaluation(Users $users, Dysfunction $dysfunction): bool
    {
        if (!is_null($dysfunction->closed_at)) {
            return false;
        }
        return $dysfunction->status == 5;
    }
    /**
     * Determine whether the Dysfunction task evaluation is closed and the dysfunction it's self can be evaluated.
     */
    public function DysEvaluation(Users $users, Dysfunction $dysfunction): bool
    {
        if (!is_null($dysfunction->closed_at)) {
            return false;
        }
        return $dysfunction->status == 7;
    }
    public function DysRunning(Users $users, Dysfunction $dysfunction): bool
    {
        if (!is_null($dysfunction->closed_at)) {
            return false;
        }
        return $dysfunction->status != 3 && $dysfunction->status != 6;
    }
}
