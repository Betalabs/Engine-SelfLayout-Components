<?php

namespace Betalabs\EngineSelfLayoutComponents\Providers;

use Betalabs\LaravelHelper\Events\GenesisCompleted;
use Betalabs\EngineSelfLayoutComponentsListeners\MapLayouts;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        GenesisCompleted::class => [
            MapLayouts::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
