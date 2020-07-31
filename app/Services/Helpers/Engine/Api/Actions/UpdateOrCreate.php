<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Actions;


use Betalabs\LaravelHelper\Services\Engine\EngineResourceCreator;
use Betalabs\LaravelHelper\Services\Engine\EngineResourceIndexer;
use Betalabs\LaravelHelper\Services\Engine\EngineResourceUpdater;

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
     * @param \Betalabs\LaravelHelper\Services\Engine\EngineResourceIndexer $indexer
     * @param \Betalabs\LaravelHelper\Services\Engine\EngineResourceUpdater $updater
     * @param \Betalabs\LaravelHelper\Services\Engine\EngineResourceCreator $creator
     *
     * @return mixed
     */
    public function execute(
        EngineResourceIndexer $indexer,
        EngineResourceUpdater $updater,
        EngineResourceCreator $creator
    ) {
        $instance = $this->firstOrCreate->execute($indexer, $creator);

        $updater->setRecordId($instance->getId());
        $instance = $updater->update();

        return $instance;
    }
}
