<?php

namespace App\Providers;

use App\Http\Repositories\ChargingSessionRepository;
use App\Http\Repositories\ChargingSessionRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ChargingSessionRepositoryInterface::class, ChargingSessionRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
