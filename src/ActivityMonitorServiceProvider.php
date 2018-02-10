<?php

namespace Zeshan77\ActivityMonitor;

use Illuminate\Support\ServiceProvider;

class ActivityMonitorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/database/migrations/' => database_path('migrations')
        ], 'migrations');
        
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
