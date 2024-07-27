<?php

namespace App\Policies;

use App\Models\Dysfunction;
use App\Models\Task;
use App\Models\Users;
use App\Scopes\YearScope;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        $tasks = Task::withoutGlobalScope(YearScope::class)
            ->select(
                'id',
                'start_date',
                'duration',
                DB::raw('DATE_ADD(start_date, INTERVAL duration DAY) AS end')
            )
            ->where('dysfunction', $dysfunction->id)
            ->get();
        return $dysfunction->status == 7 && now()->diffInDays(Carbon::parse($tasks->max('end'))) > 30;
    }
    public function DysRunning(Users $users, Dysfunction $dysfunction): bool
    {
        if (!is_null($dysfunction->closed_at)) {
            return false;
        }
        return $dysfunction->status != 3 && $dysfunction->status != 6;
    }
}
