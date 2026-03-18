<?php

namespace App\Providers;

use App\Auth\AdminUserProvider;
use App\Auth\StudentUserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Auth::provider('ebms-student', function ($app, array $config) {
            return new StudentUserProvider();
        });

        Auth::provider('ebms-admin', function ($app, array $config) {
            return new AdminUserProvider(
                $app['hash'],
                $config['model'],
            );
        });
    }
}
