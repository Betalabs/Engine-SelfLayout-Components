<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Layouts;


use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mapper;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Availables;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Unavailables;

class MapperTest extends TestCase
{
    public function testMapShouldCallAvailableAndUnavailableMappers()
    {
        $availables = \Mockery::mock(Availables::class);
        $availables->shouldReceive('map')->once();
        $unavailables = \Mockery::mock(Unavailables::class);
        $unavailables->shouldReceive('map')->once();

        $mapper = new Mapper($availables, $unavailables);
        $mapper->map();
    }
}