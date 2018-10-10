<?php

namespace Upaid\Elk\Providers;

use Illuminate\Support\ServiceProvider;

class ElkServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $path = realpath(__DIR__.'/../../config/config.php');

        $this->publishes([$path => config_path('elk.php')], 'config');
        $this->mergeConfigFrom($path, 'elk');
    }
}
