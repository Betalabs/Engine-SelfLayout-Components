<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Helpers\Engine\Api\Layouts;


use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts\Update;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;
use Betalabs\LaravelHelper\Services\Engine\ResourceUpdater;

class UpdateTest extends TestCase
{
    public function testUpdateShouldReturnAnEngineModelInstance()
    {
        $engineResourceUpdater = \Mockery::mock(ResourceUpdater::class);
        $engineResourceUpdater->makePartial();
        $engineResourceUpdater->shouldReceive('update')
            ->once()
            ->andReturn((object)['id' => 2]);

        $updater = new Update();
        $result = $updater->setRecordId(1)->setData([])->update();

        $this->assertInstanceOf(Layout::class, $result);
    }
}
