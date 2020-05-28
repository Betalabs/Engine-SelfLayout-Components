<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Layouts\Mappers;


use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts\Destroy as EngineApiLayoutDestroyer;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts\Index as EngineApiLayoutIndexer;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout as EngineLayout;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Layout;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Unavailables;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class UnavailablesTest extends TestCase
{
    public function testMapShouldHandleUnavailableOnly()
    {
        Config::shouldReceive('get')->with('layouts.unavailable')->once()
            ->andReturn([
                'test' => 'vendor/package1',
                'foo' => 'vendor/package2'
            ]);

        $json = '{"name": "Test Layout", "alias": "test-layout", "paths":{"views": "src/views", "images": "src/assets/images", "scripts": "src/assets/scripts", "styles": "src/assets/styles", "fonts": "src/assets/fonts", "videos": "src/assets/videos"}, "main_file": "main.blade.php", "colors": [{"identification": "pink", "label": "Pink", "default": true},{"identification": "blue", "label": "Blue", "default": false}], "components": [{"name": "Component 1", "description": "First component", "path": "component1", "main_file": "main.blade.php", "parameters": []}, {"name": "Component 2", "description": "Second component", "path": "component2", "main_file": "main.blade.php", "parameters": []}]}';
        File::shouldReceive('exists')
            ->twice()
            ->andReturn(true);
        File::shouldReceive('get')
            ->twice()
            ->andReturn($json);

        $engineLayout = \Mockery::mock(EngineLayout::class);
        $engineLayout->shouldReceive('getId')
            ->once()
            ->andReturn(1);
        $engineLayout->shouldReceive('getId')
            ->once()
            ->andReturn(2);

        $engineApiLayoutIndexer = \Mockery::mock(EngineApiLayoutIndexer::class);
        $engineApiLayoutIndexer->makePartial();
        $engineApiLayoutIndexer->shouldReceive('index')
            ->twice()
            ->andReturn(collect([$engineLayout]));
        $engineApiLayoutDestroyer = \Mockery::mock(EngineApiLayoutDestroyer::class);
        $engineApiLayoutDestroyer->shouldReceive('setRecordId')
            ->once()
            ->with(1)
            ->andReturnSelf();
        $engineApiLayoutDestroyer->shouldReceive('setRecordId')
            ->once()
            ->with(2)
            ->andReturnSelf();
        $engineApiLayoutDestroyer->shouldReceive('destroy')->twice();

        $availables = new Unavailables(
            $engineApiLayoutDestroyer,
            $engineApiLayoutIndexer
        );
        $availables->map();
    }

    public function testMapShouldSkipAlreadyDestroyedNotFoundByAliasLayout()
    {
        Config::shouldReceive('get')->with('layouts.unavailable')->once()
            ->andReturn([
                'test' => 'vendor/package1',
                'already-destroyed' => 'vendor/package2'
            ]);
        Config::makePartial();

        $json = '{"name": "Test Layout", "alias": "test-layout", "paths":{"views": "src/views", "images": "src/assets/images", "scripts": "src/assets/scripts", "styles": "src/assets/styles", "fonts": "src/assets/fonts", "videos": "src/assets/videos"}, "main_file": "main.blade.php", "colors": [{"identification": "pink", "label": "Pink", "default": true},{"identification": "blue", "label": "Blue", "default": false}], "components": [{"name": "Component 1", "description": "First component", "path": "component1", "main_file": "main.blade.php", "parameters": []}, {"name": "Component 2", "description": "Second component", "path": "component2", "main_file": "main.blade.php", "parameters": []}]}';
        File::shouldReceive('exists')
            ->twice()
            ->andReturn(true);
        File::shouldReceive('get')
            ->twice()
            ->andReturn($json);

        $engineLayout = \Mockery::mock(EngineLayout::class);
        $engineLayout->shouldReceive('getId')
            ->once()
            ->andReturn(2);

        $internalLayout = \Mockery::mock(Layout::class);
        $internalLayout->makePartial();
        $internalLayout->shouldReceive('getAlias')->once()
            ->andReturn('test');
        $internalLayout->shouldReceive('getAlias')->once()
            ->andReturn('already-destroyed');
        $this->app->instance(Layout::class, $internalLayout);

        $engineApiLayoutDestroyer = \Mockery::mock(EngineApiLayoutDestroyer::class);
        $engineApiLayoutDestroyer->shouldReceive('setRecordId')
            ->once()
            ->with(2)
            ->andReturnSelf();
        $engineApiLayoutDestroyer->shouldReceive('destroy')->once();

        $engineApiLayoutIndexer = \Mockery::mock(EngineApiLayoutIndexer::class);
        $engineApiLayoutIndexer->makePartial();
        $engineApiLayoutIndexer->shouldReceive('setQuery')
            ->once()
            ->with([
                'alias' => 'already-destroyed'
            ])
            ->andReturnSelf();
        $engineApiLayoutIndexer->shouldReceive('index')
            ->once()
            ->andReturn(collect());
        $engineApiLayoutIndexer->shouldReceive('setQuery')
            ->once()
            ->with([
                'alias' => 'test'
            ])
            ->andReturnSelf();
        $engineApiLayoutIndexer->shouldReceive('index')
            ->once()
            ->andReturn(collect([$engineLayout]));

        $availables = new Unavailables(
            $engineApiLayoutDestroyer,
            $engineApiLayoutIndexer
        );
        $availables->map();
    }
}