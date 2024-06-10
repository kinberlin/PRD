<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\AuthorisationRq;
use App\Models\Department;
use App\Models\Enterprise;
use App\Models\Users;
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
                if ($ents != null) {
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
    }
}
