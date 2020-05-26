<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Helpers\Engine\Api\Colors;


use Betalabs\EngineSelfLayoutComponents\Exceptions\app\Services\Helpers\Engine\Api\Colors\LayoutIsNotDefinedException;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors\Store;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Color;
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

        $creator = new Store($engineResourceCreator);
        $creator->setLayout($layout)->setData([])->create();
    }

    public function testStoreWithoutSetLayoutShouldThrowException()
    {
        $engineResourceCreator = \Mockery::mock(ResourceCreator::class);
        $engineResourceCreator->makePartial();
        $engineResourceCreator->shouldReceive('create')->never();

        $this->expectException(LayoutIsNotDefinedException::class);

        $creator = new Store($engineResourceCreator);
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

        $creator = new Store($engineResourceCreator);
        $result = $creator->setLayout($layout)->setData([])->create();

        $this->assertInstanceOf(Color::class, $result);
    }
}