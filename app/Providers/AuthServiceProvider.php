<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
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

        Gate::define('comisiones', fn($user) => $user->role_id === 1);
        Gate::define('cobranzaReportes', fn($user) => $user->role_id === 2);
        Gate::define('detalleConsumo', fn($user) => $user->role_id === 3);
        Gate::define('auxiliarCobranzaReportes', fn($user) => $user->role_id === 4);
        Gate::define('auxiliardetalleConsumo', fn($user) => $user->role_id === 5);
        Gate::define('invitado', fn($user) => $user->role_id === 6);
        Gate::define('administrador', fn($user) => $user->role_id === 7);
        Gate::define('optometria', fn($user) => $user->role_id === 8);
    }
}
