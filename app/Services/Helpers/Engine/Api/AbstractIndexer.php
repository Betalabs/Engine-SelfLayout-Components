<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api;


use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout as EngineLayout;
use Illuminate\Support\Collection;

abstract class AbstractIndexer
{
    public function index($query): Collection
    {
        return collect([
            new EngineLayout()
        ]);
    }
}