<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Actions;


use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\CreatorInterface;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\IndexerInterface;

class FirstOrCreate
{
    /**
     * Try to retrieve first instance from an index request. If index returns
     * empty, create resource at Engine API.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\IndexerInterface $indexer
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\CreatorInterface $creator
     *
     * @return mixed
     */
    public function execute(
        IndexerInterface $indexer,
        CreatorInterface $creator
    ) {
        $collection = $indexer->index();
        if (null === $instance = $collection->first()) {
            $instance = $creator->create();
        }

        return $instance;
    }
}