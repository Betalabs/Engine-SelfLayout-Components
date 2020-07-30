<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Helpers\Engine\Api\Colors;


use Betalabs\EngineSelfLayoutComponents\Exceptions\app\Services\Helpers\Engine\Api\Colors\LayoutIsNotDefinedException;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors\Index;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Color;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;
use Betalabs\LaravelHelper\Services\Engine\ResourceIndexer;

class IndexTest extends TestCase
{
    public function testIndexShouldIncludeLayoutIdIntoRouteParameters()
    {
        $layout = \Mockery::mock(Layout::class);
        $layout->shouldReceive('getId')->andReturn(123);

        $engineResourceIndexer = \Mockery::mock(ResourceIndexer::class);
        $engineResourceIndexer->makePartial();
        $engineResourceIndexer->shouldReceive('setEndpointParameters')
            ->once()
            ->with(['layoutId' => 123]);
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

        $indexer = new Index();
        $indexer->setLayout($layout)->retrieve();
    }

    public function testIndexWithoutSetLayoutShouldThrowException()
    {
        $engineResourceIndexer = \Mockery::mock(ResourceIndexer::class);
        $engineResourceIndexer->makePartial();
        $engineResourceIndexer->shouldReceive('retrieve')->never();

        $this->expectException(LayoutIsNotDefinedException::class);
        $indexer = new Index();
        $indexer->retrieve();
    }

    public function testIndexShouldReturnACollectionOfEngineModelInstances()
    {
        $layout = \Mockery::mock(Layout::class);
        $layout->shouldReceive('getId')->andReturn(123);

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

        $indexer = new Index();
        $result = $indexer->setLayout($layout)->retrieve();

        $this->assertCount(2, $result);
        $this->assertInstanceOf(Color::class, $result->first());
        $this->assertInstanceOf(Color::class, $result->last());
    }

    public function testIndexWithOffsetShouldPrepareResource()
    {
        $layout = \Mockery::mock(Layout::class);
        $layout->shouldReceive('getId')->andReturn(123);

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

        $indexer = new Index();

        $result = $indexer->setLayout($layout)->setOffset(10)->retrieve();
        $this->assertCount(2, $result);
    }
}
