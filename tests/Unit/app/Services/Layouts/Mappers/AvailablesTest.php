<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Layouts\Mappers;


use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Actions\UpdateOrCreate;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts\Index as EngineApiLayoutIndexer;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts\Update as EngineApiLayoutUpdater;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts\Store as EngineApiLayoutCreator;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout as EngineLayout;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Assets\Mapper as AssetsMapper;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Availables;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Colors\Mapper as ColorsMapper;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components\Mapper as ComponentsMapper;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class AvailablesTest extends TestCase
{
    public function testMapShouldHandleAvailableOnlyToUpdateOrCreateAndCallToMapComponentsAndColorsAndAssetsForEachThem()
    {
        Config::shouldReceive('get')->with('layouts.available')->once()
            ->andReturn([
                'test' => 'vendor/package1',
                'foo' => 'vendor/package2'
            ]);

        $componentsMapper = \Mockery::mock(ComponentsMapper::class);
        $componentsMapper->shouldReceive('map')->twice();
        $colorsMapper = \Mockery::mock(ColorsMapper::class);
        $colorsMapper->shouldReceive('map')->twice();
        $assetsMapper = \Mockery::mock(AssetsMapper::class);
        $assetsMapper->shouldReceive('map')
            ->once()
            ->withSomeOfArgs('vendor/package1');
        $assetsMapper->shouldReceive('map')
            ->once()
            ->withSomeOfArgs('vendor/package2');

        $layout = \Mockery::mock(EngineLayout::class);

        $index = \Mockery::mock(EngineApiLayoutIndexer::class);
        $index->shouldReceive('setQuery')->twice()->andReturnSelf();
        $index->shouldReceive('setLimit')->twice()->with(1)->andReturnSelf();
        $index->shouldReceive('setOffset')->twice()->with(0)->andReturnSelf();

        $update = \Mockery::mock(EngineApiLayoutUpdater::class);
        $update->shouldReceive('setData')->twice()->andReturnSelf();

        $store = \Mockery::mock(EngineApiLayoutCreator::class);
        $store->shouldReceive('setData')->twice()->andReturnSelf();

        $updateOrCreate = \Mockery::mock(UpdateOrCreate::class);
        $updateOrCreate->shouldReceive('execute')->twice()
            ->withArgs([
                $index,
                $update,
                $store
            ])
            ->andReturn($layout);

        $json = '{"name": "Test Layout", "alias": "test-layout", "paths":{"views": "src/views", "images": "src/assets/images", "scripts": "src/assets/scripts", "styles": "src/assets/styles", "fonts": "src/assets/fonts", "videos": "src/assets/videos"}, "main_file": "main.blade.php", "colors": [{"identification": "pink", "label": "Pink", "default": true},{"identification": "blue", "label": "Blue", "default": false}], "components": [{"name": "Component 1", "description": "First component", "path": "component1", "main_file": "main.blade.php", "parameters": []}, {"name": "Component 2", "description": "Second component", "path": "component2", "main_file": "main.blade.php", "parameters": []}]}';
        File::shouldReceive('exists')
            ->twice()
            ->andReturn(true);
        File::shouldReceive('get')->with(base_path("vendor/vendor/package1/configurations.json"))
            ->once()
            ->andReturn($json);
        File::shouldReceive('get')->with(base_path("vendor/vendor/package2/configurations.json"))
            ->once()
            ->andReturn($json);

        $availables = new Availables(
            $componentsMapper,
            $colorsMapper,
            $assetsMapper,
            $updateOrCreate,
            $index,
            $update,
            $store
        );
        $availables->map();
    }

    public function testMapShouldSkipPackageWhichConfigurationFileDoesNotExists()
    {
        Config::shouldReceive('get')->with('layouts.available')->once()
            ->andReturn([
                'test' => 'vendor/package1',
                'foo' => 'vendor/package2'
            ]);
        Config::makePartial();

        $componentsMapper = \Mockery::mock(ComponentsMapper::class);
        $componentsMapper->shouldReceive('map')->once();
        $colorsMapper = \Mockery::mock(ColorsMapper::class);
        $colorsMapper->shouldReceive('map')->once();
        $assetsMapper = \Mockery::mock(AssetsMapper::class);
        $assetsMapper->shouldReceive('map')->once();

        $layout = \Mockery::mock(EngineLayout::class);

        $index = \Mockery::mock(EngineApiLayoutIndexer::class);
        $index->shouldReceive('setQuery')->once()->andReturnSelf();
        $index->shouldReceive('setLimit')->once()->with(1)->andReturnSelf();
        $index->shouldReceive('setOffset')->once()->with(0)->andReturnSelf();

        $update = \Mockery::mock(EngineApiLayoutUpdater::class);
        $update->shouldReceive('setData')->once()->andReturnSelf();

        $store = \Mockery::mock(EngineApiLayoutCreator::class);
        $store->shouldReceive('setData')->once()->andReturnSelf();

        $updateOrCreate = \Mockery::mock(UpdateOrCreate::class);
        $updateOrCreate->shouldReceive('execute')->once()
            ->withArgs([
                $index,
                $update,
                $store
            ])
            ->andReturn($layout);

        $json = '{"name": "Test Layout", "alias": "test-layout", "paths":{"views": "src/views", "images": "src/assets/images", "scripts": "src/assets/scripts", "styles": "src/assets/styles", "fonts": "src/assets/fonts", "videos": "src/assets/videos"}, "main_file": "main.blade.php", "colors": [{"identification": "pink", "label": "Pink", "default": true},{"identification": "blue", "label": "Blue", "default": false}], "components": [{"name": "Component 1", "description": "First component", "path": "component1", "main_file": "main.blade.php", "parameters": []}, {"name": "Component 2", "description": "Second component", "path": "component2", "main_file": "main.blade.php", "parameters": []}]}';
        File::shouldReceive('exists')->with(base_path("vendor/vendor/package1/configurations.json"))
            ->once()
            ->andReturn(false);
        File::shouldReceive('exists')->with(base_path("vendor/vendor/package2/configurations.json"))
            ->once()
            ->andReturn(true);
        File::shouldReceive('get')->with(base_path("vendor/vendor/package1/configurations.json"))
            ->never();
        File::shouldReceive('get')->with(base_path("vendor/vendor/package2/configurations.json"))
            ->once()
            ->andReturn($json);

        $availables = new Availables(
            $componentsMapper,
            $colorsMapper,
            $assetsMapper,
            $updateOrCreate,
            $index,
            $update,
            $store
        );
        $availables->map();
    }

    public function testMapShouldSkipPackageWhichConfigurationFileWithInvalidJson()
    {
        Config::shouldReceive('get')->with('layouts.available')->once()
            ->andReturn([
                'test' => 'vendor/package1',
                'foo' => 'vendor/package2'
            ]);
        Config::makePartial();

        $componentsMapper = \Mockery::mock(ComponentsMapper::class);
        $componentsMapper->shouldReceive('map')->once();
        $colorsMapper = \Mockery::mock(ColorsMapper::class);
        $colorsMapper->shouldReceive('map')->once();
        $assetsMapper = \Mockery::mock(AssetsMapper::class);
        $assetsMapper->shouldReceive('map')->once();

        $layout = \Mockery::mock(EngineLayout::class);

        $index = \Mockery::mock(EngineApiLayoutIndexer::class);
        $index->shouldReceive('setQuery')->once()->andReturnSelf();
        $index->shouldReceive('setLimit')->once()->with(1)->andReturnSelf();
        $index->shouldReceive('setOffset')->once()->with(0)->andReturnSelf();

        $update = \Mockery::mock(EngineApiLayoutUpdater::class);
        $update->shouldReceive('setData')->once()->andReturnSelf();

        $store = \Mockery::mock(EngineApiLayoutCreator::class);
        $store->shouldReceive('setData')->once()->andReturnSelf();

        $updateOrCreate = \Mockery::mock(UpdateOrCreate::class);
        $updateOrCreate->shouldReceive('execute')->once()
            ->withArgs([
                $index,
                $update,
                $store
            ])
            ->andReturn($layout);

        $json = '{"name": "Test Layout", "alias": "test-layout", "paths":{"views": "src/views", "images": "src/assets/images", "scripts": "src/assets/scripts", "styles": "src/assets/styles", "fonts": "src/assets/fonts", "videos": "src/assets/videos"}, "main_file": "main.blade.php", "colors": [{"identification": "pink", "label": "Pink", "default": true},{"identification": "blue", "label": "Blue", "default": false}], "components": [{"name": "Component 1", "description": "First component", "path": "component1", "main_file": "main.blade.php", "parameters": []}, {"name": "Component 2", "description": "Second component", "path": "component2", "main_file": "main.blade.php", "parameters": []}]}';
        File::shouldReceive('exists')
            ->twice()
            ->andReturn(true);
        File::shouldReceive('get')->with(base_path("vendor/vendor/package1/configurations.json"))
            ->once()
            ->andReturn('invalid json content');
        File::shouldReceive('get')->with(base_path("vendor/vendor/package2/configurations.json"))
            ->once()
            ->andReturn($json);

        $availables = new Availables(
            $componentsMapper,
            $colorsMapper,
            $assetsMapper,
            $updateOrCreate,
            $index,
            $update,
            $store
        );
        $availables->map();
    }
}