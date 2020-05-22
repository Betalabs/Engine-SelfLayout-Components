<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Layouts\Mappers;


use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Availables;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Colors\Mapper as ColorsMapper;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components\Mapper as ComponentsMapper;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class AvailablesTest extends TestCase
{
    public function testMapShouldHandleAvailableOnlyAndCallToMapComponentsAndColorsForEachThem()
    {
        $componentsMapper = \Mockery::mock(ComponentsMapper::class);
        $componentsMapper->shouldReceive('map')->twice();
        $colorsMapper = \Mockery::mock(ColorsMapper::class);
        $colorsMapper->shouldReceive('map')->twice();

        Config::shouldReceive('get')->with('layouts.available')->once()
            ->andReturn([
                'test' => 'path/to/package',
                'foo' => 'bar/baz'
            ]);

        $json = '{"name": "Test Layout", "alias": "test-layout", "paths":{"views": "src/views", "images": "src/assets/images", "scripts": "src/assets/scripts", "styles": "src/assets/styles", "fonts": "src/assets/fonts", "videos": "src/assets/videos"}, "main_file": "main.blade.php", "colors": [{"identification": "pink", "label": "Pink", "default": true},{"identification": "blue", "label": "Blue", "default": false}], "components": [{"name": "Component 1", "description": "First component", "path": "component1", "main_file": "main.blade.php", "parameters": []}, {"name": "Component 2", "description": "Second component", "path": "component2", "main_file": "main.blade.php", "parameters": []}]}';
        File::shouldReceive('exists')
            ->twice()
            ->andReturn(true);
        File::shouldReceive('get')->with(base_path("vendor/path/to/package/configuration.json"))
            ->once()
            ->andReturn($json);
        File::shouldReceive('get')->with(base_path("vendor/bar/baz/configuration.json"))
            ->once()
            ->andReturn($json);

        $availables = new Availables($componentsMapper, $colorsMapper);
        $availables->map();
    }

    public function testMapShouldSkipPackageWhichConfigurationFileDoesNotExists()
    {
        $componentsMapper = \Mockery::mock(ComponentsMapper::class);
        $componentsMapper->shouldReceive('map')->once();
        $colorsMapper = \Mockery::mock(ColorsMapper::class);
        $colorsMapper->shouldReceive('map')->once();

        Config::shouldReceive('get')->with('layouts.available')->once()
            ->andReturn([
                'test' => 'path/to/package',
                'foo' => 'bar/baz'
            ]);
        Config::makePartial();

        $json = '{"name": "Test Layout", "alias": "test-layout", "paths":{"views": "src/views", "images": "src/assets/images", "scripts": "src/assets/scripts", "styles": "src/assets/styles", "fonts": "src/assets/fonts", "videos": "src/assets/videos"}, "main_file": "main.blade.php", "colors": [{"identification": "pink", "label": "Pink", "default": true},{"identification": "blue", "label": "Blue", "default": false}], "components": [{"name": "Component 1", "description": "First component", "path": "component1", "main_file": "main.blade.php", "parameters": []}, {"name": "Component 2", "description": "Second component", "path": "component2", "main_file": "main.blade.php", "parameters": []}]}';
        File::shouldReceive('exists')->with(base_path("vendor/path/to/package/configuration.json"))
            ->once()
            ->andReturn(false);
        File::shouldReceive('exists')->with(base_path("vendor/bar/baz/configuration.json"))
            ->once()
            ->andReturn(true);
        File::shouldReceive('get')->with(base_path("vendor/path/to/package/configuration.json"))
            ->never();
        File::shouldReceive('get')->with(base_path("vendor/bar/baz/configuration.json"))
            ->once()
            ->andReturn($json);

        $availables = new Availables($componentsMapper, $colorsMapper);
        $availables->map();
    }

    public function testMapShouldSkipPackageWhichConfigurationFileWithInvalidJson()
    {
        $componentsMapper = \Mockery::mock(ComponentsMapper::class);
        $componentsMapper->shouldReceive('map')->once();
        $colorsMapper = \Mockery::mock(ColorsMapper::class);
        $colorsMapper->shouldReceive('map')->once();

        Config::shouldReceive('get')->with('layouts.available')->once()
            ->andReturn([
                'test' => 'path/to/package',
                'foo' => 'bar/baz'
            ]);
        Config::makePartial();

        $json = '{"name": "Test Layout", "alias": "test-layout", "paths":{"views": "src/views", "images": "src/assets/images", "scripts": "src/assets/scripts", "styles": "src/assets/styles", "fonts": "src/assets/fonts", "videos": "src/assets/videos"}, "main_file": "main.blade.php", "colors": [{"identification": "pink", "label": "Pink", "default": true},{"identification": "blue", "label": "Blue", "default": false}], "components": [{"name": "Component 1", "description": "First component", "path": "component1", "main_file": "main.blade.php", "parameters": []}, {"name": "Component 2", "description": "Second component", "path": "component2", "main_file": "main.blade.php", "parameters": []}]}';
        File::shouldReceive('exists')
            ->twice()
            ->andReturn(true);
        File::shouldReceive('get')->with(base_path("vendor/path/to/package/configuration.json"))
            ->once()
            ->andReturn('invalid json content');
        File::shouldReceive('get')->with(base_path("vendor/bar/baz/configuration.json"))
            ->once()
            ->andReturn($json);

        $availables = new Availables($componentsMapper, $colorsMapper);
        $availables->map();
    }

    public function testMapShouldUpdateLayoutThatAlreadyExists()
    {
        // TODO
    }

    public function testMapShouldUpdateComponentsThatAlreadyExistsFromLayout()
    {
        // TODO
    }
}