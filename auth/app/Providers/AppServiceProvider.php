<?php

namespace App\Providers;

use App\Http\Services\AclService;
use App\Http\Services\AclServiceInterface;
use App\Http\Services\ChargingSessionService;
use App\Http\Services\ChargingSessionServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AclServiceInterface::class, AclService::class);
        $this->app->bind(ChargingSessionServiceInterface::class, ChargingSessionService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
