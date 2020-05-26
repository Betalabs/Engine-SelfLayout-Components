<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Helpers\Engine\Api\Components;


use Betalabs\EngineSelfLayoutComponents\Exceptions\app\Services\Helpers\Engine\Api\Components\LayoutIsNotDefinedException;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components\Find;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Component;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;
use Betalabs\LaravelHelper\Services\Engine\ResourceShower;

class FindTest extends TestCase
{
    public function testIndexShouldIncludeLayoutIdIntoRouteParameters()
    {
        $layout = \Mockery::mock(Layout::class);
        $layout->shouldReceive('getId')->andReturn(123);

        $engineResourceShower = \Mockery::mock(ResourceShower::class);
        $engineResourceShower->makePartial();
        $engineResourceShower->shouldReceive('setEndpointParameters')
            ->once()
            ->with(['layoutId' => 123]);
        $engineResourceShower->shouldReceive('retrieve')
            ->once()
            ->andReturn((object)[
                'id' => 1
            ]);

        $finder = new Find($engineResourceShower);
        $finder->setLayout($layout)->setRecordId(123)->retrieve();
    }

    public function testIndexWithoutSetLayoutShouldThrowException()
    {
        $engineResourceShower = \Mockery::mock(ResourceShower::class);
        $engineResourceShower->makePartial();
        $engineResourceShower->shouldReceive('retrieve')->never();

        $this->expectException(LayoutIsNotDefinedException::class);
        $finder = new Find($engineResourceShower);
        $finder->setRecordId(123)->retrieve();
    }

    public function testFindShouldReturnAnEngineModelInstance()
    {
        $layout = \Mockery::mock(Layout::class);
        $layout->shouldReceive('getId')->andReturn(123);

        $engineResourceShower = \Mockery::mock(ResourceShower::class);
        $engineResourceShower->makePartial();
        $engineResourceShower->shouldReceive('retrieve')
            ->once()
            ->andReturn((object)['id' => 2]);

        $finder = new Find($engineResourceShower);
        $result = $finder->setLayout($layout)->setRecordId(123)->retrieve();

        $this->assertInstanceOf(Component::class, $result);
    }
}