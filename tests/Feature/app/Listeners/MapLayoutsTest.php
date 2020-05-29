<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Feature\app\Listeners;


use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;
use Betalabs\EngineSelfLayoutComponentsListeners\MapLayouts;
use Betalabs\LaravelHelper\Events\GenesisCompleted;

class MapLayoutsTest extends TestCase
{
    public function testGenesisEventShouldMapLayouts()
    {
        $mapLayouts = \Mockery::mock(MapLayouts::class);
        $mapLayouts->shouldReceive('handle');

        $this->app->instance(
            MapLayouts::class,
            $mapLayouts
        );

        event(GenesisCompleted::class);
    }
}