<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Layouts\Mappers\Colors;


use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Actions\UpdateOrCreate;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors\Destroy as EngineApiDestroyColors;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors\Index as EngineApiIndexColors;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors\Store as EngineApiStoreColors;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors\Update as EngineApiUpdateColors;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Colors\Color;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Colors\Mapper;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Layout;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Color as EngineColor;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout as EngineLayout;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;

class MapperTest extends TestCase
{
    public function testMapShouldUpdateOrCreateEachColor()
    {
        $engineApiColorIndexer = \Mockery::mock(EngineApiIndexColors::class);
        $engineApiColorIndexer->makePartial();
        $engineApiColorIndexer->shouldReceive('setLayout')
            ->times(3)
            ->andReturnSelf();
        $engineApiColorIndexer->shouldReceive('setQuery')
            ->once()
            ->with([
                'identification' => 'color-1'
            ])
            ->andReturnSelf();
        $engineApiColorIndexer->shouldReceive('setQuery')
            ->once()
            ->with([
                'identification' => 'color-2'
            ])
            ->andReturnSelf();
        $engineApiColorIndexer->shouldReceive('retrieve')
            ->once()
            ->andReturn(collect());

        $engineApiColorCreator = \Mockery::mock(EngineApiStoreColors::class);
        $engineApiColorCreator->makePartial();
        $engineApiColorCreator->shouldReceive('setLayout')
            ->twice()
            ->andReturnSelf();
        $engineApiColorCreator->shouldReceive('setData')
            ->once()
            ->with([
                'identification' => 'color-1',
                'label' => 'Color 1',
                'default' => true
            ])
            ->andReturnSelf();
        $engineApiColorCreator->shouldReceive('setData')
            ->once()
            ->with([
                'identification' => 'color-2',
                'label' => 'Color 2',
                'default' => false
            ])
            ->andReturnSelf();

        $engineApiColorUpdater = \Mockery::mock(EngineApiUpdateColors::class);
        $engineApiColorUpdater->makePartial();
        $engineApiColorUpdater->shouldReceive('setLayout')
            ->twice()
            ->andReturnSelf();
        $engineApiColorUpdater->shouldReceive('setData')
            ->once()
            ->with([
                'identification' => 'color-1',
                'label' => 'Color 1',
                'default' => true
            ])
            ->andReturnSelf();
        $engineApiColorUpdater->shouldReceive('setData')
            ->once()
            ->with([
                'identification' => 'color-2',
                'label' => 'Color 2',
                'default' => false
            ])
            ->andReturnSelf();

        $engineApiColorDestroyer = \Mockery::mock(EngineApiDestroyColors::class);
        $engineApiColorDestroyer->makePartial();
        $engineApiColorDestroyer->shouldReceive('setLayout')->never();
        $engineApiColorDestroyer->shouldReceive('setRecordId')->never();
        $engineApiColorDestroyer->shouldReceive('destroy')->never();

        $engineColor1 = \Mockery::mock(EngineColor::class);
        $engineColor1->shouldReceive('getId')->andReturn(1);
        $engineColor2 = \Mockery::mock(EngineColor::class);
        $engineColor2->shouldReceive('getId')->andReturn(2);

        $updateOrCreate = \Mockery::mock(UpdateOrCreate::class);
        $updateOrCreate->shouldReceive('execute')
            ->once()
            ->with(
                $engineApiColorIndexer,
                $engineApiColorUpdater,
                $engineApiColorCreator
            )
            ->andReturn($engineColor1);
        $updateOrCreate->shouldReceive('execute')
            ->once()
            ->with(
                $engineApiColorIndexer,
                $engineApiColorUpdater,
                $engineApiColorCreator
            )
            ->andReturn($engineColor2);

        $color1 = \Mockery::mock(Color::class);
        $color1->shouldReceive('getIdentification')->andReturn('color-1');
        $color1->shouldReceive('getLabel')->andReturn('Color 1');
        $color1->shouldReceive('isDefault')->andReturn(true);

        $color2 = \Mockery::mock(Color::class);
        $color2->shouldReceive('getIdentification')->andReturn('color-2');
        $color2->shouldReceive('getLabel')->andReturn('Color 2');
        $color2->shouldReceive('isDefault')->andReturn(false);

        $internalLayout = \Mockery::mock(Layout::class);
        $internalLayout->shouldReceive('getColors')->andReturn(collect([
            $color1,
            $color2
        ]));
        $engineLayout = \Mockery::mock(EngineLayout::class);

        $mapper = new Mapper(
            $updateOrCreate,
            $engineApiColorIndexer,
            $engineApiColorCreator,
            $engineApiColorUpdater,
            $engineApiColorDestroyer
        );
        $mapper->map($engineLayout, $internalLayout);
    }

    public function testMapShouldRemoveUnusedColors()
    {
        $engineColorToRemove = \Mockery::mock(EngineColor::class);
        $engineColorToRemove->shouldReceive('getId')->andReturn(3);

        $engineApiColorIndexer = \Mockery::mock(EngineApiIndexColors::class);
        $engineApiColorIndexer->makePartial();
        $engineApiColorIndexer->shouldReceive('setLayout')
            ->times(3)
            ->andReturnSelf();
        $engineApiColorIndexer->shouldReceive('setQuery')
            ->once()
            ->with([
                'identification' => 'color-1'
            ])
            ->andReturnSelf();
        $engineApiColorIndexer->shouldReceive('setQuery')
            ->once()
            ->with([
                'identification' => 'color-2'
            ])
            ->andReturnSelf();
        $engineApiColorIndexer->shouldReceive('setQuery')
            ->once()
            ->with([
                'id-not-in' => '1,2',
                '_fields' => 'id'
            ])
            ->andReturnSelf();
        $engineApiColorIndexer->shouldReceive('retrieve')
            ->once()
            ->andReturn(collect([
                $engineColorToRemove
            ]));

        $engineApiColorCreator = \Mockery::mock(EngineApiStoreColors::class);
        $engineApiColorCreator->makePartial();
        $engineApiColorCreator->shouldReceive('setLayout')
            ->twice()
            ->andReturnSelf();

        $engineApiColorUpdater = \Mockery::mock(EngineApiUpdateColors::class);
        $engineApiColorUpdater->makePartial();
        $engineApiColorUpdater->shouldReceive('setLayout')
            ->twice()
            ->andReturnSelf();

        $engineApiColorDestroyer = \Mockery::mock(EngineApiDestroyColors::class);
        $engineApiColorDestroyer->makePartial();
        $engineApiColorDestroyer->shouldReceive('setLayout')
            ->once()
            ->andReturnSelf();
        $engineApiColorDestroyer->shouldReceive('setRecordId')
            ->once()
            ->with(3)
            ->andReturnSelf();
        $engineApiColorDestroyer->shouldReceive('destroy')
            ->once();

        $engineColor1 = \Mockery::mock(EngineColor::class);
        $engineColor1->shouldReceive('getId')->andReturn(1);
        $engineColor2 = \Mockery::mock(EngineColor::class);
        $engineColor2->shouldReceive('getId')->andReturn(2);

        $updateOrCreate = \Mockery::mock(UpdateOrCreate::class);
        $updateOrCreate->shouldReceive('execute')
            ->once()
            ->with(
                $engineApiColorIndexer,
                $engineApiColorUpdater,
                $engineApiColorCreator
            )
            ->andReturn($engineColor1);
        $updateOrCreate->shouldReceive('execute')
            ->once()
            ->with(
                $engineApiColorIndexer,
                $engineApiColorUpdater,
                $engineApiColorCreator
            )
            ->andReturn($engineColor2);

        $color1 = \Mockery::mock(Color::class);
        $color1->shouldReceive('getIdentification')->andReturn('color-1');
        $color1->shouldReceive('getLabel')->andReturn('Color 1');
        $color1->shouldReceive('isDefault')->andReturn(true);

        $color2 = \Mockery::mock(Color::class);
        $color2->shouldReceive('getIdentification')->andReturn('color-2');
        $color2->shouldReceive('getLabel')->andReturn('Color 2');
        $color2->shouldReceive('isDefault')->andReturn(false);

        $internalLayout = \Mockery::mock(Layout::class);
        $internalLayout->shouldReceive('getColors')->andReturn(collect([
            $color1,
            $color2
        ]));
        $engineLayout = \Mockery::mock(EngineLayout::class);

        $mapper = new Mapper(
            $updateOrCreate,
            $engineApiColorIndexer,
            $engineApiColorCreator,
            $engineApiColorUpdater,
            $engineApiColorDestroyer
        );
        $mapper->map($engineLayout, $internalLayout);
    }
}