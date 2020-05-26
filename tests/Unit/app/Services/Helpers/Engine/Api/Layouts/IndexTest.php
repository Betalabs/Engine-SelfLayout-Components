<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Helpers\Engine\Api\Layouts;


use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts\Index;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;
use Betalabs\LaravelHelper\Services\Engine\ResourceIndexer;

class IndexTest extends TestCase
{
    public function testIndexShouldReturnACollectionOfEngineModelInstances()
    {
        $engineResourceIndexer = \Mockery::mock(ResourceIndexer::class);
        $engineResourceIndexer->makePartial();
        $engineResourceIndexer->shouldReceive('retrieve')
            ->once()
            ->andReturn(collect([
                (object)[
                    'id' => 1
                ],
                (object)[
                    'id' => 2
                ]
            ]));

        $indexer = new Index($engineResourceIndexer);
        $result = $indexer->index();

        $this->assertCount(2, $result);
        $this->assertInstanceOf(Layout::class, $result->first());
        $this->assertInstanceOf(Layout::class, $result->last());
    }

    public function testIndexWithOffsetShouldPrepareResource()
    {
        $engineResourceIndexer = \Mockery::mock(ResourceIndexer::class);
        $engineResourceIndexer->makePartial();
        $engineResourceIndexer->shouldReceive('setOffset')
            ->once()
            ->with(10);
        $engineResourceIndexer->shouldReceive('retrieve')
            ->once()
            ->andReturn(collect([
                (object)[
                    'id' => 1
                ],
                (object)[
                    'id' => 2
                ]
            ]));

        $indexer = new Index($engineResourceIndexer);

        $result = $indexer->setOffset(10)->index();
        $this->assertCount(2, $result);
    }
}