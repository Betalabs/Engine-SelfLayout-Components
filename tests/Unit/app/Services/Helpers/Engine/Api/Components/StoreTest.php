<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Helpers\Engine\Api\Components;


use Betalabs\EngineSelfLayoutComponents\Exceptions\app\Services\Helpers\Engine\Api\Components\LayoutIsNotDefinedException;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components\Store;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;
use Betalabs\LaravelHelper\Services\Engine\ResourceCreator;

class StoreTest extends TestCase
{
    public function testStoreWithoutSetLayoutShouldThrowException()
    {
        $engineResourceCreator = \Mockery::mock(ResourceCreator::class);
        $engineResourceCreator->makePartial();
        $engineResourceCreator->shouldReceive('create')->never();

        $this->expectException(LayoutIsNotDefinedException::class);

        $creator = new Store();
        $creator->setData([])->create();
    }
}
