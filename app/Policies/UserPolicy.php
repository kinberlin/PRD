<?php

namespace App\Policies;

use App\Models\AuthorisationPilote;
use App\Models\AuthorisationRq;
use App\Models\Enterprise;
use App\Models\Processes;
use App\Models\Users;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    /**
     * Determine whether the user is Admin or not.
     */
    public function isAdmin(Users $users): bool
    {
        return $users->role == 1;
    }
    /**
     * Determine whether the user is RQ or not.
     */
    public function isRq(Users $user): bool
    {
        $rqU = AuthorisationRq::where('interim', 0)->get();
        $users = Users::whereIn('id', $rqU->pluck('user'))->where('role', '<>', 1)->get();
        return $users->where('id', $user->id)->first() !== null ? true : false;
    }
    /**
     * Determine whether the user is Pilote or not.
     */
    public function isPilote(Users $user): bool
    {
        $pltU = AuthorisationPilote::where('interim', 0)->get();
        $users = Users::whereIn('id', $pltU->pluck('user'))->where('role', '<>', 1)->get();
        return $users->where('id', $user->id)->first() !== null ? true : false;
    }
    /**
     * Determine whether the user is Pilote or not.
     */
    public function isProcessusPilote(Users $user, Processes $proc): bool
    {
        $pltU = AuthorisationPilote::where('interim', 0)->where('process', $proc->id)->get();
        $users = Users::whereIn('id', $pltU->pluck('user'))->where('role', '<>', 1)->get();
        return $users->where('id', $user->id)->first() !== null ? true : false;
    }



}
