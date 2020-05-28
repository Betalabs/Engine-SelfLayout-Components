<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers;


use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Actions\UpdateOrCreate;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts\Index as EngineApiLayoutIndexer;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts\Update as EngineApiLayoutUpdater;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts\Store as EngineApiLayoutCreator;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Assets\Mapper as AssetsMapper;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Colors\Mapper as ColorsMapper;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components\Mapper as ComponentsMapper;
use Illuminate\Support\Facades\Config;

class Availables extends AbstractMapper
{
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components\Mapper */
    private $componentsMapper;
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Colors\Mapper */
    private $colorsMapper;
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Actions\UpdateOrCreate */
    private $updateOrCreate;
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts\Index */
    private $engineApiLayoutIndexer;
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts\Update */
    private $engineApiLayoutUpdater;
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts\Store */
    private $engineApiLayoutCreator;
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Assets\Mapper */
    private $assetsMapper;

    /**
     * Availables constructor.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components\Mapper         $componentsMapper
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Colors\Mapper             $colorsMapper
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Assets\Mapper             $assetsMapper
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Actions\UpdateOrCreate $updateOrCreate
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts\Index          $engineApiLayoutIndexer
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts\Update         $engineApiLayoutUpdater
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts\Store          $engineApiLayoutCreator
     */
    public function __construct(
        ComponentsMapper $componentsMapper,
        ColorsMapper $colorsMapper,
        AssetsMapper $assetsMapper,
        UpdateOrCreate $updateOrCreate,
        EngineApiLayoutIndexer $engineApiLayoutIndexer,
        EngineApiLayoutUpdater $engineApiLayoutUpdater,
        EngineApiLayoutCreator $engineApiLayoutCreator
    ) {
        $this->componentsMapper = $componentsMapper;
        $this->colorsMapper = $colorsMapper;
        $this->assetsMapper = $assetsMapper;
        $this->updateOrCreate = $updateOrCreate;
        $this->engineApiLayoutIndexer = $engineApiLayoutIndexer;
        $this->engineApiLayoutUpdater = $engineApiLayoutUpdater;
        $this->engineApiLayoutCreator = $engineApiLayoutCreator;
    }

    /**
     * Map available layouts and send to Engine API.
     */
    public function map()
    {
        $layouts = $this->parseLayouts();
        foreach ($layouts as $packageName => $layout) {
            $engineLayout = $this->createLayout($layout);

            $this->componentsMapper->map($engineLayout, $layout);
            $this->colorsMapper->map($engineLayout, $layout);
            $this->assetsMapper->map($packageName, $layout);
            // TODO Criar pacote helper que monta a URL do asset no s3 para disponibilizar no layout de forma a referenciar esse asset no codigo.
        }
    }

    /**
     * Try to find Layout at Engine API using Layout alias and update them if
     * exists or create if not exists.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Layout $layout
     *
     * @return \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout
     */
    private function createLayout(Layout $layout)
    {
        $this->prepareIndexer($layout);
        $this->prepareUpdater($layout);
        $this->prepareCreator($layout);

        return $this->updateOrCreate->execute(
            $this->engineApiLayoutIndexer,
            $this->engineApiLayoutUpdater,
            $this->engineApiLayoutCreator
        );
    }

    /**
     * Prepare indexer API resource setting layout data.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Layout $layout
     */
    private function prepareIndexer(Layout $layout)
    {
        $this->engineApiLayoutIndexer
            ->setQuery([
                'alias' => $layout->getAlias()
            ])
            ->setLimit(1)
            ->setOffset(0);
    }

    /**
     * Prepare updater API resource setting layout data.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Layout $layout
     */
    private function prepareUpdater(Layout $layout)
    {
        $this->engineApiLayoutUpdater->setData([
            'name' => $layout->getName(),
            'alias' => $layout->getAlias()
        ]);
    }

    /**
     * Prepare creator API resource setting layout data.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Layout $layout
     */
    private function prepareCreator(Layout $layout)
    {
        $this->engineApiLayoutCreator->setData([
            'name' => $layout->getName(),
            'alias' => $layout->getAlias()
        ]);
    }

    /**
     * Retrieve from all layouts configurations.
     *
     * @return array
     */
    protected function retrieveLayouts(): array
    {
        return Config::get('layouts.available');
    }
}