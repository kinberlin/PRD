<?php

namespace App\Policies;

use App\Models\Site;
use App\Models\Users;
use Illuminate\Auth\Access\Response;

class SitePolicy
{
    /**
     * Determine whether the Site is deletable.
     * By ensuring it dont have any dependency data, it is said deletable.
     */
    public function canSiteDelete(Users $users, Site $site): bool
    {
        if(is_null($site)){
            return false;
        }
         return ($site->dysfunctions()->exists() ? false : true);
    }
    /**
     * Determine whether the Site is is visible on signal form.
     */
    public function isSiteVisible(Users $users, Site $site): bool
    {
        if(is_null($site)){
            return false;
        }
        return $site->visible;
    }
}
