<?php

namespace Betalabs\EngineSelfLayoutComponentsListeners;


use Betalabs\LaravelHelper\Events\GenesisCompleted;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mapper;

class MapLayouts extends AbstractEngineIntegratedListener
{
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mapper */
    private $layoutsMapper;

    /**
     * MapComponents constructor.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mapper $layoutsMapper
     */
    public function __construct(Mapper $layoutsMapper)
    {
        $this->layoutsMapper = $layoutsMapper;
    }

    /**
     * Authenticate and map layouts packages to Engine API.
     *
     * @param \Betalabs\LaravelHelper\Events\GenesisCompleted $event
     */
    public function handle(GenesisCompleted $event)
    {
        $this->authenticate($event->tenant);

        $this->layoutsMapper->map();
    }
}