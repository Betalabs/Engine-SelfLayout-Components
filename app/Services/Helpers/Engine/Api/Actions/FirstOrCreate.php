<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Actions;


use Betalabs\LaravelHelper\Services\Engine\EngineResourceCreator;
use Betalabs\LaravelHelper\Services\Engine\EngineResourceIndexer;

class FirstOrCreate
{
    /**
     * Try to retrieve first instance from an index request. If index returns
     * empty, create resource at Engine API.
     *
     * @param \Betalabs\LaravelHelper\Services\Engine\EngineResourceIndexer $indexer
     * @param \Betalabs\LaravelHelper\Services\Engine\EngineResourceCreator $creator
     *
     * @return mixed
     */
    public function execute(
        EngineResourceIndexer $indexer,
        EngineResourceCreator $creator
    ) {
        $collection = $indexer->retrieve();
        if (null === $instance = $collection->first()) {
            $instance = $creator->create();
        }

        return $instance;
    }
}
