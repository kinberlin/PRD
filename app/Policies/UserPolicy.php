<?php

namespace App\Policies;

use App\Models\AuthorisationPilote;
use App\Models\AuthorisationRq;
use App\Models\Users;
use Illuminate\Auth\Access\Response;

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
     * Determine whether the user can view any models.
     */
    public function viewAny(Users $users): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Users $users, Users $userss): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Users $users): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Users $users, Users $userss): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Users $users, Users $userss): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Users $users, Users $userss): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Users $users, Users $userss): bool
    {
        //
    }
}
