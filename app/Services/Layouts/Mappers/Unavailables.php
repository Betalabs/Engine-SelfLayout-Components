<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Layouts;


use Betalabs\EngineSelfLayoutComponents\Exceptions\app\Services\Layouts\Mappers\LayoutNotFoundOnEngineApiException;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts\Destroy as EngineApiLayoutDestroyer;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts\Index as EngineApiLayoutIndexer;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout as EngineLayout;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\AbstractMapper;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Layout;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class Unavailables extends AbstractMapper
{
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts\Destroy */
    private $engineApiLayoutDestroyer;
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts\Index */
    private $engineApiLayoutIndexer;

    /**
     * Unavailables constructor.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts\Destroy $engineApiLayoutDestroyer
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts\Index   $engineApiLayoutIndexer
     */
    public function __construct(
        EngineApiLayoutDestroyer $engineApiLayoutDestroyer,
        EngineApiLayoutIndexer $engineApiLayoutIndexer
    ) {
        $this->engineApiLayoutDestroyer = $engineApiLayoutDestroyer;
        $this->engineApiLayoutIndexer = $engineApiLayoutIndexer;
    }

    /**
     * Map unavailable layouts and remove them from Engine API.
     */
    public function map()
    {
        $layouts = $this->parseLayouts();
        foreach ($layouts as $layout) {
            try {
                $this->deleteLayout($layout);
            } catch (LayoutNotFoundOnEngineApiException $e) {
                Log::info($e->getMessage());
            }
        }
    }

    /**
     * Call Engine API to delete layout instance.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Layout $layout
     *
     */
    private function deleteLayout(Layout $layout)
    {
        $engineLayout = $this->findEngineLayout($layout);
        if (null === $engineLayout) {
            throw new LayoutNotFoundOnEngineApiException(
                "Layout `{$layout->getName()}` not found on Engine API"
            );
        }

        $this->engineApiLayoutDestroyer->destroy($engineLayout->getId());
    }

    /**
     * Find layout instance from Engine API.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Layout $layout
     *
     * @return \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout
     */
    private function findEngineLayout(Layout $layout): ?EngineLayout
    {
        return $this->engineApiLayoutIndexer
            ->setQuery([
                'alias' => $layout->getAlias()
            ])
            ->setLimit(1)
            ->setOffset(0)
            ->index()
            ->first();
    }

    /**
     * Retrieve from all layouts configurations.
     *
     * @return array
     */
    protected function retrieveLayouts(): array
    {
        return Config::get('layouts.unavailable');
    }
}