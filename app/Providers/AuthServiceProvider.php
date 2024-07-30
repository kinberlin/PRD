<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\AuthorisationPilote;
use App\Models\AuthorisationRq;
use App\Models\Department;
use App\Models\Dysfunction;
use App\Models\Enterprise;
use App\Models\Gravity;
use App\Models\Invitation;
use App\Models\Probability;
use App\Models\Processes;
use App\Models\Site;
use App\Models\Users;
use App\Policies\DepartmentPolicy;
use App\Policies\DysfunctionPolicy;
use App\Policies\GravityPolicy;
use App\Policies\InvitationPolicy;
use App\Policies\ProbabilityPolicy;
use App\Policies\SitePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Users::class => UserPolicy::class,
        Invitation::class => InvitationPolicy::class,
        Dysfunction::class => DysfunctionPolicy::class,
        Department::class => DepartmentPolicy::class,
        Site::class => SitePolicy::class,
        Gravity::class => GravityPolicy::class,
        Probability::class => ProbabilityPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        /**
         * Determine whether the user is RQ or not.
         */
        Gate::define('isEnterpriseRQ', function (Users $user, Enterprise $ents): bool {
            if ($user->access == 1) {
                if (!is_null($ents)) {
                    $rqU = AuthorisationRq::where('enterprise', $ents->id)->get();
                    $users = Users::whereIn('id', $rqU->pluck('user'))->where('role', '<>', 1)->get();
                    return $users->where('id', $user->id)->first() != null ? true : false;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        });
        /**
         * Determine whether the user is Pilote or not.
         */
        Gate::define('isProcessusPilote', function (Users $user, Processes $proc): bool {
            if ($user->access == 1) {
                if (!is_null($proc)) {
                    $pltU = AuthorisationPilote::where('process', $proc->id)->get();
                    $users = Users::whereIn('id', $pltU->pluck('user'))->where('role', '<>', 1)->get();
                    return $users->where('id', $user->id)->first() !== null ? true : false;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        });
    }
}
