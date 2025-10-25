<?php

namespace App\Providers;

use App\Models\Abstract\Consumer;
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
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /**
         * Only admin (Admin model type=admin) can manage.
         *
         * @param Consumer $consumer
         * @return bool
         */
        Gate::define('superAdmin', function (Consumer $consumer) {
            return $consumer->type === 'admin';
        });

        /**
         * Check if admin/moderator has permission for a scope and abilities.
         *
         * @param Consumer $consumer
         * @param string $scope
         * @param string $abilities
         * @param string $boolean
         * @return bool
         */
        Gate::define('permits', function (Consumer $consumer, string $scope, string $abilities, string $boolean = 'and') {
            return $consumer->isPermits($scope, $abilities, $boolean);
        });
    }
}
