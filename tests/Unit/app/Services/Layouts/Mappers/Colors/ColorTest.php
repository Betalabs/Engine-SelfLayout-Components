<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Layouts\Mappers\Colors;


use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Colors\Color;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;

class ColorTest extends TestCase
{
    public function testFromArrayShouldFillLayoutPropertiesAndNestedRelatedValues()
    {
        $json = '{"identification": "pink","label": "Pink","default": true}';
        $data = json_decode($json, true);
        $color = Color::fromArray($data);

        $this->assertEquals(
            $color->getIdentification(),
            'pink'
        );
        $this->assertEquals(
            $color->getLabel(),
            'Pink'
        );
        $this->assertEquals(
            $color->isDefault(),
            true
        );
    }
}