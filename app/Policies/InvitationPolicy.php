<?php

namespace App\Policies;

use App\Models\Invitation;
use App\Models\Users;
use Illuminate\Auth\Access\Response;

class InvitationPolicy
{
    /**
     * Determine whether the invitation is open or closed.
     */
    public function isInvitationOpen(Users $users, Invitation $invitation): bool
    {
        if($invitation == null){
            return false;
        }
        return is_null($invitation->closed_at);
    }
}
