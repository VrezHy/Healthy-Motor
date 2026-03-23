<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\RegisterService;
use App\Services\UserRole;
use App\Services\OwnerRegistration;
use App\Services\PasswordHasher;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(RegisterService::class, function ($app){
            return new RegisterService(
                new UserRole(),
                new OwnerRegistration(),
                new PasswordHasher(),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
