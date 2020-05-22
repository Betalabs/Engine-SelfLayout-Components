<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Helpers\Engine\Api\Actions;


use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Actions\FirstOrCreate;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Actions\UpdateOrCreate;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\CreatorInterface;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\IndexerInterface;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\UpdaterInterface;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\EngineModelInterface;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;

class UpdateOrCreateTest extends TestCase
{
    public function testExecuteShouldPerformUpdateFromFirstOrCreateResult()
    {
        $instance = \Mockery::mock(EngineModelInterface::class);
        $instance->shouldReceive('getId')
            ->once()
            ->andReturn(123);

        $indexer = \Mockery::mock(IndexerInterface::class);
        $creator = \Mockery::mock(CreatorInterface::class);

        $updater = \Mockery::mock(UpdaterInterface::class);
        $updater->shouldReceive('setRecordId')->once()->with(123);
        $updater->shouldReceive('update')->andReturn($instance);

        $firstOrCreate = \Mockery::mock(FirstOrCreate::class);
        $firstOrCreate->shouldReceive('execute')
            ->withArgs([$indexer, $creator])
            ->andReturn($instance);

        $updateOrCreate = new UpdateOrCreate($firstOrCreate);
        $updateOrCreate->execute(
            $indexer,
            $updater,
            $creator
        );
    }
}