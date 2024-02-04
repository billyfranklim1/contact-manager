<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ContactService;
use App\Services\ContactServiceInterface;
use App\Repositories\ContactRepositoryInterface;
use App\Repositories\ContactRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ContactRepositoryInterface::class,
            ContactRepository::class,
        );

        $this->app->bind(
            ContactServiceInterface::class,
            ContactService::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
