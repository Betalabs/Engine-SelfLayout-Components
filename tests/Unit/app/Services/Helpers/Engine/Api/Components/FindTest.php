<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Helpers\Engine\Api\Components;


use Betalabs\EngineSelfLayoutComponents\Exceptions\app\Services\Helpers\Engine\Api\Components\LayoutIsNotDefinedException;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components\Find;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;
use Betalabs\LaravelHelper\Services\Engine\ResourceShower;

class FindTest extends TestCase
{
    public function testRetrieveWithoutSetLayoutShouldThrowException()
    {
        $engineResourceShower = \Mockery::mock(ResourceShower::class);
        $engineResourceShower->makePartial();
        $engineResourceShower->shouldReceive('retrieve')->never();

        $this->expectException(LayoutIsNotDefinedException::class);
        $finder = new Find();
        $finder->setRecordId(123)->retrieve();
    }
}
