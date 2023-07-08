<?php

namespace Mangosteen\Core\Providers;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/filesystems.php' => config_path('filesystems.php'),
            __DIR__.'/../../config/media-library.php' => config_path('media-library.php'),
            __DIR__.'/../../config/tags.php' => config_path('tags.php'),
            __DIR__.'/../../config/jwt.php' => config_path('jwt.php'),
        ], 'config');
    }

    public function register()
    {
        // ...
    }
}
