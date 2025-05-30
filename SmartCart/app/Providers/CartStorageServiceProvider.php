<?php

namespace App\Providers;

use App\Services\DatabaseCartStorage;
use Illuminate\Support\ServiceProvider;

class CartStorageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Register the database storage for the shopping cart
        // This allows for database persistence when config('shopping_cart.persistent_storage') is true
        $this->app->singleton('cart.storage', function($app) {
            return new DatabaseCartStorage();
        });
    }
} 