<?php

namespace App\Providers;

use App\Services\ChargingSessionService;
use App\Services\ChargingSessionServiceInterface;
use App\Services\CircuitBreakerServer;
use App\Services\CircuitBreakerServerInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ChargingSessionServiceInterface::class, ChargingSessionService::class);
        $this->app->bind(CircuitBreakerServerInterface::class, CircuitBreakerServer::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
