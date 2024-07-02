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
        if ($user->access == 1) {
            $rqU = AuthorisationRq::all();
            $users = Users::whereIn('id', $rqU->pluck('user'))->where('role', '<>', 1)->get();
            return $users->where('id', $user->id)->first() !== null ? true : false;
        } else {
            return false;
        }
    }
    /**
     * Determine whether the user is Pilote or not.
     */
    public function isPilote(Users $user): bool
    {
        if ($user->access == 1) {
            $pltU = AuthorisationPilote::all();
            $users = Users::whereIn('id', $pltU->pluck('user'))->where('role', '<>', 1)->get();
            return $users->where('id', $user->id)->first() !== null ? true : false;
        } else {
            return false;
        }
    }

}
