<?php

namespace App\Providers;

use App\Contracts\LayerInterface;
use App\Contracts\LayupInterface;
use App\Contracts\SuppliersInterface;
use App\Services\LayerService;
use App\Services\LayupService;
use App\Services\SupplierService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            SuppliersInterface::class,
            SupplierService::class,
            LayupInterface::class,
            LayupService::class,
            LayerInterface::class,
            LayerService::class
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
