<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // RNF 2.2: la regla de acceso vive aquí, no en el controlador
        Gate::define('ver-metricas', function (User $user): bool {
            return $user->role === 'admin';
        });
    }
}