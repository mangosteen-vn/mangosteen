<?php

namespace Mangosteen\Product\Providers;

use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
    }

    /**
     * Register any package services
     *
     * @return void
     */
    public function register()
    {
    }
}
