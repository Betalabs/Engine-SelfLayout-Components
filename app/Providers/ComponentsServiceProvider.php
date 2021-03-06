<?php

namespace Betalabs\EngineSelfLayoutComponents\Providers;

use Illuminate\Support\ServiceProvider;

class ComponentsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'engine-components');
        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/engine-components'),
        ]);
    }
}
