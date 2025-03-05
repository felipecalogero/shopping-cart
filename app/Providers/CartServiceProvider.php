<?php

namespace App\Providers;

use App\Infrastructure\Repositories\CartRepository;
use core\Modules\Cart\Repositories\CartRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            CartRepositoryInterface::class,
            CartRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
