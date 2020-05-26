<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Layouts\Mappers\Components\Parameter;


use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components\Parameters\Parameter;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;
use Illuminate\Support\Collection;

class ParameterTest extends TestCase
{
    public function testFromArrayShouldFillParameterData()
    {
        $json = '{"name": "param1","label": "Param 1","description": "Test param 1","possible_values": ["a", "b"]}';
        $data = json_decode($json, true);
        $parameter = Parameter::fromArray($data);

        $this->assertEquals(
            $parameter->getName(),
            'param1'
        );
        $this->assertEquals(
            $parameter->getLabel(),
            'Param 1'
        );
        $this->assertEquals(
            $parameter->getDescription(),
            'Test param 1'
        );
        $this->assertInstanceOf(
            Collection::class,
            $parameter->getPossibleValues()
        );
        $this->assertCount(
            2,
            $parameter->getPossibleValues()
        );
        $this->assertEquals(
            'a',
            $parameter->getPossibleValues()->first()
        );
        $this->assertEquals(
            'b',
            $parameter->getPossibleValues()->last()
        );
    }
}