<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Helpers\Engine\Api\Actions;


use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Actions\FirstOrCreate;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\CreatorInterface;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\IndexerInterface;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;

class FirstOrCreateTest extends TestCase
{
    public function testExecuteShouldReturnFirstResultFromIndex()
    {
        $indexer = \Mockery::mock(IndexerInterface::class);
        $indexer->shouldReceive('index')->once()->andReturn(collect([
            'a'
        ]));
        $creator = \Mockery::mock(CreatorInterface::class);
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
        $indexer = \Mockery::mock(IndexerInterface::class);
        $indexer->shouldReceive('index')->once()->andReturn(collect());
        $creator = \Mockery::mock(CreatorInterface::class);
        $creator->shouldReceive('create')->once()->andReturn('b');

        $firstOrCreate = new FirstOrCreate();
        $result = $firstOrCreate->execute(
            $indexer,
            $creator
        );

        $this->assertEquals('b', $result);
    }
}