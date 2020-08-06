<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Layouts\Mappers\Components;


use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Actions\UpdateOrCreate;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components\Destroy as EngineApiDestroyComponents;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components\Index as EngineApiIndexComponents;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components\Store as EngineApiStoreComponents;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components\Update as EngineApiUpdateComponents;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components\Component;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components\Mapper;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components\Parameters\Parameter;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Layout;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Component as EngineComponent;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout as EngineLayout;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;

class MapperTest extends TestCase
{
    public function testMapShouldUpdateOrCreateEachComponent()
    {
        $engineApiComponentIndexer = \Mockery::mock(EngineApiIndexComponents::class);
        $engineApiComponentIndexer->makePartial();
        $engineApiComponentIndexer->shouldReceive('setLayout')
            ->times(3)
            ->andReturnSelf();
        $engineApiComponentIndexer->shouldReceive('setQuery')
            ->once()
            ->with([
                'name' => 'Name 1'
            ])
            ->andReturnSelf();
        $engineApiComponentIndexer->shouldReceive('setQuery')
            ->once()
            ->with([
                'name' => 'Name 2'
            ])
            ->andReturnSelf();
        $engineApiComponentIndexer->shouldReceive('retrieve')
            ->once()
            ->andReturn(collect());

        $engineApiComponentCreator = \Mockery::mock(EngineApiStoreComponents::class);
        $engineApiComponentCreator->makePartial();
        $engineApiComponentCreator->shouldReceive('setLayout')
            ->twice()
            ->andReturnSelf();
        $engineApiComponentCreator->shouldReceive('setData')
            ->once()
            ->with([
                'name' => 'Name 1',
                'description' => 'Desc 1',
                'path' => 'path/to/component/1',
                'main_file' => 'main-file1.blade.php',
                'parameters' => [
                    [
                        'name' => 'param1',
                        'label' => 'Param 1',
                        'description' => 'Test param 1',
                        'possible_values' => ['test']
                    ]
                ]
            ])
            ->andReturnSelf();
        $engineApiComponentCreator->shouldReceive('setData')
            ->once()
            ->with([
                'name' => 'Name 2',
                'description' => 'Desc 2',
                'path' => 'path/to/component/2',
                'main_file' => 'main-file2.blade.php',
                'parameters' => []
            ])
            ->andReturnSelf();

        $engineApiComponentUpdater = \Mockery::mock(EngineApiUpdateComponents::class);
        $engineApiComponentUpdater->makePartial();
        $engineApiComponentUpdater->shouldReceive('setLayout')
            ->twice()
            ->andReturnSelf();
        $engineApiComponentUpdater->shouldReceive('setData')
            ->once()
            ->with([
                'name' => 'Name 1',
                'description' => 'Desc 1',
                'path' => 'path/to/component/1',
                'main_file' => 'main-file1.blade.php',
                'parameters' => [
                    [
                        'name' => 'param1',
                        'label' => 'Param 1',
                        'description' => 'Test param 1',
                        'possible_values' => ['test']
                    ]
                ]
            ])
            ->andReturnSelf();
        $engineApiComponentUpdater->shouldReceive('setData')
            ->once()
            ->with([
                'name' => 'Name 2',
                'description' => 'Desc 2',
                'path' => 'path/to/component/2',
                'main_file' => 'main-file2.blade.php',
                'parameters' => []
            ])
            ->andReturnSelf();

        $engineApiComponentDestroyer = \Mockery::mock(EngineApiDestroyComponents::class);
        $engineApiComponentDestroyer->makePartial();
        $engineApiComponentDestroyer->shouldReceive('setLayout')->never();
        $engineApiComponentDestroyer->shouldReceive('setRecordId')->never();
        $engineApiComponentDestroyer->shouldReceive('destroy')->never();

        $engineComponent1 = \Mockery::mock(EngineComponent::class);
        $engineComponent1->shouldReceive('getId')->andReturn(1);
        $engineComponent2 = \Mockery::mock(EngineComponent::class);
        $engineComponent2->shouldReceive('getId')->andReturn(2);

        $updateOrCreate = \Mockery::mock(UpdateOrCreate::class);
        $updateOrCreate->shouldReceive('execute')
            ->once()
            ->with(
                $engineApiComponentIndexer,
                $engineApiComponentUpdater,
                $engineApiComponentCreator
            )
            ->andReturn($engineComponent1);
        $updateOrCreate->shouldReceive('execute')
            ->once()
            ->with(
                $engineApiComponentIndexer,
                $engineApiComponentUpdater,
                $engineApiComponentCreator
            )
            ->andReturn($engineComponent2);

        $param1 = \Mockery::mock(Parameter::class);
        $param1->shouldReceive('getName')->andReturn('param1');
        $param1->shouldReceive('getLabel')->andReturn('Param 1');
        $param1->shouldReceive('getDescription')->andReturn('Test param 1');
        $param1->shouldReceive('getPossibleValues')->andReturn(collect([
            'test'
        ]));

        $component1 = \Mockery::mock(Component::class);
        $component1->shouldReceive('getName')->andReturn('Name 1');
        $component1->shouldReceive('getDescription')->andReturn('Desc 1');
        $component1->shouldReceive('getPath')->andReturn('path/to/component/1');
        $component1->shouldReceive('getMainFile')->andReturn('main-file1.blade.php');
        $component1->shouldReceive('getParameters')->andReturn(collect([
            $param1
        ]));
        $component2 = \Mockery::mock(Component::class);
        $component2->shouldReceive('getName')->andReturn('Name 2');
        $component2->shouldReceive('getDescription')->andReturn('Desc 2');
        $component2->shouldReceive('getPath')->andReturn('path/to/component/2');
        $component2->shouldReceive('getMainFile')->andReturn('main-file2.blade.php');
        $component2->shouldReceive('getParameters')->andReturn(collect());

        $internalLayout = \Mockery::mock(Layout::class);
        $internalLayout->shouldReceive('getComponents')->andReturn(collect([
            $component1,
            $component2
        ]));
        $engineLayout = \Mockery::mock(EngineLayout::class);

        $mapper = new Mapper(
            $updateOrCreate,
            $engineApiComponentIndexer,
            $engineApiComponentCreator,
            $engineApiComponentUpdater,
            $engineApiComponentDestroyer
        );
        $mapper->map($engineLayout, $internalLayout);
    }

    public function testMapShouldRemoveUnusedComponents()
    {
        $engineComponentToRemove = \Mockery::mock(EngineComponent::class);
        $engineComponentToRemove->shouldReceive('getId')->andReturn(3);

        $engineApiComponentIndexer = \Mockery::mock(EngineApiIndexComponents::class);
        $engineApiComponentIndexer->makePartial();
        $engineApiComponentIndexer->shouldReceive('setLayout')
            ->times(3)
            ->andReturnSelf();
        $engineApiComponentIndexer->shouldReceive('setQuery')
            ->once()
            ->with([
                'name' => 'Name 1'
            ])
            ->andReturnSelf();
        $engineApiComponentIndexer->shouldReceive('setQuery')
            ->once()
            ->with([
                'name' => 'Name 2'
            ])
            ->andReturnSelf();
        $engineApiComponentIndexer->shouldReceive('setQuery')
            ->once()
            ->with([
                'id-not-in' => '1,2',
                '_fields' => 'id'
            ])
            ->andReturnSelf();
        $engineApiComponentIndexer->shouldReceive('retrieve')
            ->once()
            ->andReturn(collect([
                $engineComponentToRemove
            ]));

        $engineApiComponentCreator = \Mockery::mock(EngineApiStoreComponents::class);
        $engineApiComponentCreator->makePartial();
        $engineApiComponentCreator->shouldReceive('setLayout')
            ->twice()
            ->andReturnSelf();

        $engineApiComponentUpdater = \Mockery::mock(EngineApiUpdateComponents::class);
        $engineApiComponentUpdater->makePartial();
        $engineApiComponentUpdater->shouldReceive('setLayout')
            ->twice()
            ->andReturnSelf();

        $engineApiComponentDestroyer = \Mockery::mock(EngineApiDestroyComponents::class);
        $engineApiComponentDestroyer->makePartial();
        $engineApiComponentDestroyer->shouldReceive('setLayout')
            ->once()
            ->andReturnSelf();
        $engineApiComponentDestroyer->shouldReceive('setRecordId')
            ->once()
            ->with(3)
            ->andReturnSelf();
        $engineApiComponentDestroyer->shouldReceive('destroy')
            ->once();

        $engineComponent1 = \Mockery::mock(EngineComponent::class);
        $engineComponent1->shouldReceive('getId')->andReturn(1);
        $engineComponent2 = \Mockery::mock(EngineComponent::class);
        $engineComponent2->shouldReceive('getId')->andReturn(2);

        $updateOrCreate = \Mockery::mock(UpdateOrCreate::class);
        $updateOrCreate->shouldReceive('execute')
            ->once()
            ->with(
                $engineApiComponentIndexer,
                $engineApiComponentUpdater,
                $engineApiComponentCreator
            )
            ->andReturn($engineComponent1);
        $updateOrCreate->shouldReceive('execute')
            ->once()
            ->with(
                $engineApiComponentIndexer,
                $engineApiComponentUpdater,
                $engineApiComponentCreator
            )
            ->andReturn($engineComponent2);

        $param1 = \Mockery::mock(Parameter::class);
        $param1->shouldReceive('getName')->andReturn('test');
        $param1->shouldReceive('getLabel')->andReturn('test');
        $param1->shouldReceive('getDescription')->andReturn('test');
        $param1->shouldReceive('getPossibleValues')->andReturn(collect([
            'test'
        ]));

        $component1 = \Mockery::mock(Component::class);
        $component1->shouldReceive('getName')->andReturn('Name 1');
        $component1->shouldReceive('getDescription')->andReturn('test');
        $component1->shouldReceive('getPath')->andReturn('test');
        $component1->shouldReceive('getMainFile')->andReturn('test');
        $component1->shouldReceive('getParameters')->andReturn(collect([
            $param1
        ]));
        $component2 = \Mockery::mock(Component::class);
        $component2->shouldReceive('getName')->andReturn('Name 2');
        $component2->shouldReceive('getDescription')->andReturn('test');
        $component2->shouldReceive('getPath')->andReturn('test');
        $component2->shouldReceive('getMainFile')->andReturn('test');
        $component2->shouldReceive('getParameters')->andReturn(collect());

        $internalLayout = \Mockery::mock(Layout::class);
        $internalLayout->shouldReceive('getComponents')->andReturn(collect([
            $component1,
            $component2
        ]));
        $engineLayout = \Mockery::mock(EngineLayout::class);

        $mapper = new Mapper(
            $updateOrCreate,
            $engineApiComponentIndexer,
            $engineApiComponentCreator,
            $engineApiComponentUpdater,
            $engineApiComponentDestroyer
        );
        $mapper->map($engineLayout, $internalLayout);
    }
}