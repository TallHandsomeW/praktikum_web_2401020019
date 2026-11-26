<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('kelola-buku', function ($user) {
            return $user->role === 'admin';
        });
    }
}