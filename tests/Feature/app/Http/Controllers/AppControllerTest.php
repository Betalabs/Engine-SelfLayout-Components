<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Feature\app\Http\Controllers;


use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;
use Betalabs\EngineSelfLayoutComponentsListeners\MapLayouts;

class AppControllerTest extends TestCase
{
    public function testRegisterShouldCallLayoutsMapper()
    {
        $mapLayouts = \Mockery::mock(MapLayouts::class);
        $mapLayouts->shouldReceive('handle');

        $this->app->instance(
            MapLayouts::class,
            $mapLayouts
        );

        $this->postJson(
            '/api/apps/genesis',
            [
                'tenant' => [
                    'name' => 'Store 1',
                    'email' => 'store@company.com',
                ],

                'engine_registry.registry_id' => '123456789',
                'engine_registry.api_base_uri' => 'http://store1.api.betalabs.net',
                'engine_registry.api_access_token' => '123456789abcdef123',
            ]
        );
    }
}
