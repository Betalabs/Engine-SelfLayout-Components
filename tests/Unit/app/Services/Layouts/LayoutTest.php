<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Layouts;


use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Layout;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;
use Illuminate\Support\Collection;

class LayoutTest extends TestCase
{
    public function testFromJsonShouldFillLayoutProperties()
    {
        $json = '{"name": "Test Layout", "alias": "test-layout", "paths":{"views": "src/views", "images": "src/assets/images", "scripts": "src/assets/scripts", "styles": "src/assets/styles", "fonts": "src/assets/fonts", "videos": "src/assets/videos"}, "main_file": "main.blade.php", "colors": [{"identification": "pink", "label": "Pink", "default": true},{"identification": "blue", "label": "Blue", "default": false}], "components": [{"name": "Component 1", "description": "First component", "path": "component1", "main_file": "main.blade.php", "parameters": []}, {"name": "Component 2", "description": "Second component", "path": "component2", "main_file": "main.blade.php", "parameters": []}]}';
        $data = json_decode($json, true);
        $layout = Layout::fromArray($data);

        $this->assertEquals(
            $layout->getName(),
            'Test Layout'
        );
        $this->assertEquals(
            $layout->getAlias(),
            'test-layout'
        );
        $this->assertEquals(
            $layout->getViewsPath(),
            'src/views'
        );
        $this->assertEquals(
            $layout->getImagesPath(),
            'src/assets/images'
        );
        $this->assertEquals(
            $layout->getStylesPath(),
            'src/assets/styles'
        );
        $this->assertEquals(
            $layout->getFontsPath(),
            'src/assets/fonts'
        );
        $this->assertEquals(
            $layout->getVideosPath(),
            'src/assets/videos'
        );
        $this->assertEquals(
            $layout->getMainFile(),
            'main.blade.php'
        );
        $this->assertInstanceOf(
            Collection::class,
            $layout->getColors()
        );
        $this->assertCount(
            2,
            $layout->getColors()
        );
        $this->assertInstanceOf(
            Collection::class,
            $layout->getComponents()
        );
        $this->assertCount(
            2,
            $layout->getComponents()
        );
    }
}