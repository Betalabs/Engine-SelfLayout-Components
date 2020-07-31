<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Helpers\Engine\Api\Components;


use Betalabs\EngineSelfLayoutComponents\Exceptions\app\Services\Helpers\Engine\Api\Components\LayoutIsNotDefinedException;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components\Store;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Component;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;
use Betalabs\LaravelHelper\Services\Engine\ResourceCreator;

class StoreTest extends TestCase
{
    public function testStoreShouldIncludeLayoutIdIntoRouteParameters()
    {
        $layout = \Mockery::mock(Layout::class);
        $layout->shouldReceive('getId')->andReturn(123);

        $engineResourceCreator = \Mockery::mock(ResourceCreator::class);
        $engineResourceCreator->makePartial();
        $engineResourceCreator->shouldReceive('setEndpointParameters')
            ->once()
            ->with(['layoutId' => 123]);
        $engineResourceCreator->shouldReceive('create')
            ->once()
            ->andReturn((object)['id' => 2]);

        $creator = new Store();
        $creator->setLayout($layout)->setData([])->create();
    }

    public function testStoreWithoutSetLayoutShouldThrowException()
    {
        $engineResourceCreator = \Mockery::mock(ResourceCreator::class);
        $engineResourceCreator->makePartial();
        $engineResourceCreator->shouldReceive('create')->never();

        $this->expectException(LayoutIsNotDefinedException::class);

        $creator = new Store();
        $creator->setData([])->create();
    }

    public function testStoreShouldReturnAnEngineModelInstance()
    {
        $layout = \Mockery::mock(Layout::class);
        $layout->shouldReceive('getId')->andReturn(123);

        $engineResourceCreator = \Mockery::mock(ResourceCreator::class);
        $engineResourceCreator->makePartial();
        $engineResourceCreator->shouldReceive('create')
            ->once()
            ->andReturn((object)['id' => 2]);

        $creator = new Store();
        $result = $creator->setLayout($layout)->setData([])->create();

        $this->assertInstanceOf(Component::class, $result);
    }
}
