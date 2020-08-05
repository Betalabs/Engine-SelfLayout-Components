<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Helpers\Engine\Api\Colors;


use Betalabs\EngineSelfLayoutComponents\Exceptions\app\Services\Helpers\Engine\Api\Colors\LayoutIsNotDefinedException;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors\Update;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Color;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;
use Betalabs\LaravelHelper\Services\Engine\ResourceUpdater;

class UpdateTest extends TestCase
{
    public function testStoreWithoutSetLayoutShouldThrowException()
    {
        $this->expectException(LayoutIsNotDefinedException::class);

        $updater = new Update();
        $updater->setRecordId(1)->setData([])->update();
    }
}
