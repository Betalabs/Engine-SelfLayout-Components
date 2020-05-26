<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Helpers\Engine\Api\Layouts;


use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts\Find;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;
use Betalabs\LaravelHelper\Services\Engine\ResourceShower;

class FindTest extends TestCase
{
    public function testFindShouldReturnAnEngineModelInstance()
    {
        $engineResourceShower = \Mockery::mock(ResourceShower::class);
        $engineResourceShower->makePartial();
        $engineResourceShower->shouldReceive('retrieve')
            ->once()
            ->andReturn((object)['id' => 2]);

        $finder = new Find($engineResourceShower);
        $result = $finder->setRecordId(123)->retrieve();

        $this->assertInstanceOf(Layout::class, $result);
    }
}