<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Colors;


use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Actions\UpdateOrCreate;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors\Destroy as EngineApiDestroyColors;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors\Index as EngineApiIndexColors;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors\Store as EngineApiStoreColors;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors\Update as EngineApiUpdateColors;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Color as EngineColor;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout as EngineLayout;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Layout;
use Illuminate\Support\Collection;

class Mapper
{
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Actions\UpdateOrCreate */
    private $updateOrCreate;
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors\Index */
    private $engineApiColorIndexer;
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors\Store */
    private $engineApiColorCreator;
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors\Update */
    private $engineApiColorUpdater;
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors\Destroy */
    private $engineApiColorDestroyer;

    /**
     * Mapper constructor.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Actions\UpdateOrCreate $updateOrCreate
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors\Index       $engineApiColorIndexer
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors\Store       $engineApiColorCreator
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors\Update      $engineApiColorUpdater
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors\Destroy     $engineApiColorDestroyer
     */
    public function __construct(
        UpdateOrCreate $updateOrCreate,
        EngineApiIndexColors $engineApiColorIndexer,
        EngineApiStoreColors $engineApiColorCreator,
        EngineApiUpdateColors $engineApiColorUpdater,
        EngineApiDestroyColors $engineApiColorDestroyer
    ) {
        $this->updateOrCreate = $updateOrCreate;
        $this->engineApiColorIndexer = $engineApiColorIndexer;
        $this->engineApiColorCreator = $engineApiColorCreator;
        $this->engineApiColorUpdater = $engineApiColorUpdater;
        $this->engineApiColorDestroyer = $engineApiColorDestroyer;
    }

    /**
     * Loop into layout colors and update or create each them into Engine API.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout $engineLayout
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Layout       $internalLayout
     *
     * @return \Illuminate\Support\Collection
     */
    public function map(EngineLayout $engineLayout, Layout $internalLayout)
    {
        $colors = collect();
        foreach ($internalLayout->getColors() as $color) {
            $engineColor = $this->createColor($engineLayout, $color);
            $colors->push($engineColor);
        }
        $this->removeDeprecatedColors($engineLayout, $colors);

        return $colors;
    }

    /**
     * Remove all colors related to layout which ids not in recently
     * created/updated colors ids.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout $engineLayout
     * @param \Illuminate\Support\Collection                                             $colors
     */
    private function removeDeprecatedColors(
        EngineLayout $engineLayout,
        Collection $colors
    ) {
        $colorsToDestroy = $this->retrieveDeprecatedColors(
            $engineLayout,
            $colors
        );
        $colorsIds = $colorsToDestroy->map(function (EngineColor $color) {
            return $color->getId();
        });

        foreach ($colorsIds as $colorId) {
            $this->engineApiColorDestroyer
                ->setLayout($engineLayout)
                ->setRecordId($colorId)
                ->destroy();
        }
    }

    /**
     * Get all colors where ids is different than recently created/updated ids.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout $engineLayout
     * @param \Illuminate\Support\Collection                                             $colors
     *
     * @return \Illuminate\Support\Collection
     */
    private function retrieveDeprecatedColors(
        EngineLayout $engineLayout,
        Collection $colors
    ): Collection {
        $colorsIds = $colors->map(function (EngineColor $color) {
            return $color->getId();
        });

        if ($colorsIds->isEmpty()) {
            return collect();
        }

        return $this->engineApiColorIndexer
            ->setLayout($engineLayout)
            ->setOffset(0)
            ->setLimit(100)
            ->setQuery([
                'id-not-in' => $colorsIds->implode(','),
                '_fields' => 'id'
            ])
            ->index();
    }

    /**
     * Try to find Color at Engine API using their identification and update
     * them if exists or create if not exists.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout $engineLayout
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Colors\Color $color
     *
     * @return \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Color
     */
    private function createColor(
        EngineLayout $engineLayout,
        Color $color
    ) {
        $this->prepareIndexer($engineLayout, $color);
        $this->prepareUpdater($engineLayout, $color);
        $this->prepareCreator($engineLayout, $color);

        return $this->updateOrCreate->execute(
            $this->engineApiColorIndexer,
            $this->engineApiColorUpdater,
            $this->engineApiColorCreator
        );
    }

    /**
     * Prepare indexer API resource setting color data.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout $engineLayout
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Colors\Color $color
     */
    private function prepareIndexer(
        EngineLayout $engineLayout,
        Color $color
    ) {
        $this->engineApiColorIndexer->setLayout($engineLayout)
            ->setQuery([
                'identification' => $color->getIdentification()
            ])
            ->setLimit(1)
            ->setOffset(0);
    }

    /**
     * Prepare updater API resource setting color data.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout $engineLayout
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Colors\Color $color
     */
    private function prepareUpdater(
        EngineLayout $engineLayout,
        Color $color
    ) {
        $this->engineApiColorUpdater->setLayout($engineLayout)
            ->setData([
                'identification' => $color->getIdentification(),
                'label' => $color->getLabel(),
                'default' => $color->isDefault()
            ]);
    }

    /**
     * Prepare creator API resource setting color data.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout $engineLayout
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Colors\Color $color
     */
    private function prepareCreator(
        EngineLayout $engineLayout,
        Color $color
    ) {
        $this->engineApiColorCreator->setLayout($engineLayout)
            ->setData([
                'identification' => $color->getIdentification(),
                'label' => $color->getLabel(),
                'default' => $color->isDefault()
            ]);
    }
}