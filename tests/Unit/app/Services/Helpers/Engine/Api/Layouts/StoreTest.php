<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Helpers\Engine\Api\Layouts;


use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts\Store;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;
use Betalabs\LaravelHelper\Services\Engine\ResourceCreator;

class StoreTest extends TestCase
{
    public function testStoreShouldReturnAnEngineModelInstance()
    {
        $engineResourceCreator = \Mockery::mock(ResourceCreator::class);
        $engineResourceCreator->makePartial();
        $engineResourceCreator->shouldReceive('create')
            ->once()
            ->andReturn((object)['id' => 2]);

        $creator = new Store();
        $result = $creator->setData([])->create();

        $this->assertInstanceOf(Layout::class, $result);
    }
}
