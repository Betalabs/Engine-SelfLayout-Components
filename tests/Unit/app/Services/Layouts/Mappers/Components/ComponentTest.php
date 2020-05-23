<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Layouts\Mappers\Components;


use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components\Component;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components\Parameters\Parameter;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;
use Illuminate\Support\Collection;

class ComponentTest extends TestCase
{
    public function testFromArrayShouldFillLayoutPropertiesAndNestedRelatedValues()
    {
        $json = '{"name": "Component 2", "description": "Second component", "path": "component2", "main_file": "main.blade.php", "parameters": [{"name": "param1","label": "First parameter","description": "First parameter to test","possible_values": ["a", "b"]}, {"name": "second_param","label": "Second parameter","description": "Second parameter for test","possible_values": ["a"]}]}';
        $data = json_decode($json, true);
        $component = Component::fromArray($data);

        $this->assertEquals(
            $component->getName(),
            'Component 2'
        );
        $this->assertEquals(
            $component->getDescription(),
            'Second component'
        );
        $this->assertEquals(
            $component->getPath(),
            'component2'
        );
        $this->assertEquals(
            $component->getMainFile(),
            'main.blade.php'
        );
        $this->assertInstanceOf(
            Collection::class,
            $component->getParameters()
        );
        $this->assertCount(
            2,
            $component->getParameters()
        );
        $this->assertInstanceOf(
            Parameter::class,
            $component->getParameters()->first()
        );
        $this->assertInstanceOf(
            Parameter::class,
            $component->getParameters()->last()
        );
    }
}