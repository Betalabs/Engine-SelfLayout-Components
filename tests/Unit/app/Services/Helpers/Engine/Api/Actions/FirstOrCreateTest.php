<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Helpers\Engine\Api\Actions;


use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Actions\FirstOrCreate;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;
use Betalabs\LaravelHelper\Services\Engine\EngineResourceCreator;
use Betalabs\LaravelHelper\Services\Engine\EngineResourceIndexer;

class FirstOrCreateTest extends TestCase
{
    public function testExecuteShouldReturnFirstResultFromIndex()
    {
        $indexer = \Mockery::mock(EngineResourceIndexer::class);
        $indexer->shouldReceive('retrieve')->once()->andReturn(collect([
            'a'
        ]));
        $creator = \Mockery::mock(EngineResourceCreator::class);
        $creator->shouldReceive('create')->never();

        $firstOrCreate = new FirstOrCreate();
        $result = $firstOrCreate->execute(
            $indexer,
            $creator
        );

        $this->assertEquals('a', $result);
    }

    public function testExecuteShouldCallCreatorWhenIndexReturnsEmpty()
    {
        $indexer = \Mockery::mock(EngineResourceIndexer::class);
        $indexer->shouldReceive('retrieve')->once()->andReturn(collect());
        $creator = \Mockery::mock(EngineResourceCreator::class);
        $creator->shouldReceive('create')->once()->andReturn('b');

        $firstOrCreate = new FirstOrCreate();
        $result = $firstOrCreate->execute(
            $indexer,
            $creator
        );

        $this->assertEquals('b', $result);
    }
}
