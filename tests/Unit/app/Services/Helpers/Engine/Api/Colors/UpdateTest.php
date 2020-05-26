<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Helpers\Engine\Api\Colors;


use Betalabs\EngineSelfLayoutComponents\Exceptions\app\Services\Helpers\Engine\Api\Colors\LayoutIsNotDefinedException;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors\Update;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Color;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;
use Betalabs\LaravelHelper\Services\Engine\ResourceUpdater;

class UpdateTest extends TestCase
{
    public function testStoreShouldIncludeLayoutIdIntoRouteParameters()
    {
        $layout = \Mockery::mock(Layout::class);
        $layout->shouldReceive('getId')->andReturn(123);

        $engineResourceUpdater = \Mockery::mock(ResourceUpdater::class);
        $engineResourceUpdater->makePartial();
        $engineResourceUpdater->shouldReceive('setEndpointParameters')
            ->once()
            ->with(['layoutId' => 123]);
        $engineResourceUpdater->shouldReceive('update')
            ->once()
            ->andReturn((object)['id' => 2]);

        $creator = new Update($engineResourceUpdater);
        $creator->setLayout($layout)->setRecordId(1)->setData([])->update();
    }

    public function testStoreWithoutSetLayoutShouldThrowException()
    {
        $engineResourceUpdater = \Mockery::mock(ResourceUpdater::class);
        $engineResourceUpdater->makePartial();
        $engineResourceUpdater->shouldReceive('update')->never();

        $this->expectException(LayoutIsNotDefinedException::class);

        $updater = new Update($engineResourceUpdater);
        $updater->setRecordId(1)->setData([])->update();
    }

    public function testUpdateShouldReturnAnEngineModelInstance()
    {
        $layout = \Mockery::mock(Layout::class);
        $layout->shouldReceive('getId')->andReturn(123);

        $engineResourceUpdater = \Mockery::mock(ResourceUpdater::class);
        $engineResourceUpdater->makePartial();
        $engineResourceUpdater->shouldReceive('update')
            ->once()
            ->andReturn((object)['id' => 2]);

        $updater = new Update($engineResourceUpdater);
        $result = $updater->setLayout($layout)->setRecordId(1)->setData([])->update();

        $this->assertInstanceOf(Color::class, $result);
    }
}