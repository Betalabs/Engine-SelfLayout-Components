<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Helpers\Engine\Api\Components;


use Betalabs\EngineSelfLayoutComponents\Exceptions\app\Services\Helpers\Engine\Api\Components\LayoutIsNotDefinedException;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components\Update;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;
use Betalabs\LaravelHelper\Services\Engine\ResourceUpdater;

class UpdateTest extends TestCase
{
    public function testStoreWithoutSetLayoutShouldThrowException()
    {
        $engineResourceUpdater = \Mockery::mock(ResourceUpdater::class);
        $engineResourceUpdater->makePartial();
        $engineResourceUpdater->shouldReceive('update')->never();

        $this->expectException(LayoutIsNotDefinedException::class);

        $updater = new Update();
        $updater->setRecordId(1)->setData([])->update();
    }
}
