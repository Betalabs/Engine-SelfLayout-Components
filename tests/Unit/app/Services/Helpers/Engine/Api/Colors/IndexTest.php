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
    public function testIndexWithoutSetLayoutShouldThrowException()
    {
        $engineResourceIndexer = \Mockery::mock(ResourceIndexer::class);
        $engineResourceIndexer->makePartial();
        $engineResourceIndexer->shouldReceive('retrieve')->never();

        $this->expectException(LayoutIsNotDefinedException::class);
        $indexer = new Index();
        $indexer->retrieve();
    }
}
