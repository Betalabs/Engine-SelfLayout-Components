<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components;


use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Actions\UpdateOrCreate;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components\Destroy as EngineApiDestroyComponents;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components\Index as EngineApiIndexComponents;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components\Store as EngineApiStoreComponents;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components\Update as EngineApiUpdateComponents;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Component as EngineComponent;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout as EngineLayout;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components\Parameters\Parameter;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Layout;
use Illuminate\Support\Collection;

class Mapper
{
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Actions\UpdateOrCreate */
    private $updateOrCreate;
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components\Index */
    private $engineApiComponentIndexer;
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components\Store */
    private $engineApiComponentCreator;
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components\Update */
    private $engineApiComponentUpdater;
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components\Destroy */
    private $engineApiComponentDestroyer;

    /**
     * Mapper constructor.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Actions\UpdateOrCreate $updateOrCreate
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components\Index       $engineApiComponentIndexer
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components\Store       $engineApiComponentCreator
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components\Update      $engineApiComponentUpdater
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components\Destroy     $engineApiComponentDestroyer
     */
    public function __construct(
        UpdateOrCreate $updateOrCreate,
        EngineApiIndexComponents $engineApiComponentIndexer,
        EngineApiStoreComponents $engineApiComponentCreator,
        EngineApiUpdateComponents $engineApiComponentUpdater,
        EngineApiDestroyComponents $engineApiComponentDestroyer
    ) {
        $this->updateOrCreate = $updateOrCreate;
        $this->engineApiComponentIndexer = $engineApiComponentIndexer;
        $this->engineApiComponentCreator = $engineApiComponentCreator;
        $this->engineApiComponentUpdater = $engineApiComponentUpdater;
        $this->engineApiComponentDestroyer = $engineApiComponentDestroyer;
    }

    /**
     * Loop into layout components and update or create each them into Engine
     * API.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout $engineLayout
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Layout       $internalLayout
     *
     * @return \Illuminate\Support\Collection
     */
    public function map(EngineLayout $engineLayout, Layout $internalLayout)
    {
        $components = collect();
        foreach ($internalLayout->getComponents() as $component) {
            $engineComponent = $this->createComponent($engineLayout, $component);
            $components->push($engineComponent);
        }
        $this->removeDeprecatedComponents($engineLayout, $components);

        return $components;
    }

    /**
     * Remove all components related to layout which ids not in recently
     * created/updated components ids.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout $engineLayout
     * @param \Illuminate\Support\Collection|EngineComponent[]                           $components
     */
    private function removeDeprecatedComponents(
        EngineLayout $engineLayout,
        Collection $components
    ) {
        $componentsToDestroy = $this->retrieveDeprecatedComponents(
            $engineLayout,
            $components
        );
        $componentsIds = $componentsToDestroy->map(function (EngineComponent $component) {
            return $component->getId();
        });

        foreach ($componentsIds as $componentId) {
            $this->engineApiComponentDestroyer
                ->setLayout($engineLayout)
                ->setRecordId($componentId)
                ->destroy();
        }
    }

    /**
     * Get all components where ids is different than recently
     * created/updated ids.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout $engineLayout
     * @param \Illuminate\Support\Collection                                             $components
     *
     * @return \Illuminate\Support\Collection
     */
    private function retrieveDeprecatedComponents(
        EngineLayout $engineLayout,
        Collection $components
    ): Collection {
        $componentsIds = $components->map(function (EngineComponent $component) {
            return $component->getId();
        });

        if ($componentsIds->isEmpty()) {
            return collect();
        }

        return $this->engineApiComponentIndexer
            ->setLayout($engineLayout)
            ->setOffset(0)
            ->setLimit(100)
            ->setQuery([
                'id-not-in' => $componentsIds->implode(','),
                '_fields' => 'id'
            ])
            ->index();
    }

    /**
     * Try to find Component at Engine API using their alias and update them if
     * exists or create if not exists.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout         $engineLayout
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components\Component $component
     *
     * @return \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Component
     */
    private function createComponent(
        EngineLayout $engineLayout,
        Component $component
    ) {
        $this->prepareIndexer($engineLayout, $component);
        $this->prepareUpdater($engineLayout, $component);
        $this->prepareCreator($engineLayout, $component);

        return $this->updateOrCreate->execute(
            $this->engineApiComponentIndexer,
            $this->engineApiComponentUpdater,
            $this->engineApiComponentCreator
        );
    }

    /**
     * Prepare indexer API resource setting component data.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout         $engineLayout
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components\Component $component
     */
    private function prepareIndexer(
        EngineLayout $engineLayout,
        Component $component
    ) {
        $this->engineApiComponentIndexer->setLayout($engineLayout)
            ->setQuery([
                'name' => $component->getName()
            ])
            ->setLimit(1)
            ->setOffset(0);
    }

    /**
     * Prepare updater API resource setting component data.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout         $engineLayout
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components\Component $component
     */
    private function prepareUpdater(
        EngineLayout $engineLayout,
        Component $component
    ) {
        $this->engineApiComponentUpdater->setLayout($engineLayout)
            ->setData([
                'name' => $component->getName(),
                'description' => $component->getDescription(),
                'path' => $component->getPath(),
                'main_file' => $component->getMainFile(),
                'parameters' => $component->getParameters()
                    ->map(function (Parameter $parameter) {
                        return [
                            'name' => $parameter->getName(),
                            'label' => $parameter->getLabel(),
                            'description' => $parameter->getDescription(),
                            'possible_values' => $parameter->getPossibleValues()->values()->all()
                        ];
                    })
                    ->all()
            ]);
    }

    /**
     * Prepare creator API resource setting component data.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout         $engineLayout
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components\Component $component
     */
    private function prepareCreator(
        EngineLayout $engineLayout,
        Component $component
    ) {
        $this->engineApiComponentCreator->setLayout($engineLayout)
            ->setData([
                'name' => $component->getName(),
                'description' => $component->getDescription(),
                'path' => $component->getPath(),
                'main_file' => $component->getMainFile(),
                'parameters' => $component->getParameters()
                    ->map(function (Parameter $parameter) {
                        return [
                            'name' => $parameter->getName(),
                            'label' => $parameter->getLabel(),
                            'description' => $parameter->getDescription(),
                            'possible_values' => $parameter->getPossibleValues()->values()->all()
                        ];
                    })
                    ->all()
            ]);
    }
}