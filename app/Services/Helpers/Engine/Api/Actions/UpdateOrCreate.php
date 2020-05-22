<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Actions;


use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\CreatorInterface;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\IndexerInterface;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\UpdaterInterface;

class UpdateOrCreate
{
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Actions\FirstOrCreate */
    private $firstOrCreate;

    /**
     * UpdateOrCreate constructor.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Actions\FirstOrCreate $firstOrCreate
     */
    public function __construct(FirstOrCreate $firstOrCreate)
    {
        $this->firstOrCreate = $firstOrCreate;
    }

    /**
     * Try to retrieve first instance to update from an index request. If
     * index returns empty, create resource at Engine API.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\IndexerInterface $indexer
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\UpdaterInterface $updater
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\CreatorInterface $creator
     *
     * @return mixed
     */
    public function execute(
        IndexerInterface $indexer,
        UpdaterInterface $updater,
        CreatorInterface $creator
    ) {
        $instance = $this->firstOrCreate->execute($indexer, $creator);

        $updater->setRecordId($instance->getId());
        $instance = $updater->update();

        return $instance;
    }
}