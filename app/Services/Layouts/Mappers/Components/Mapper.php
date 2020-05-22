<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components;


use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Layout;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout as EngineLayout;

class Mapper
{
    public function map(EngineLayout $engineLayout, Layout $internalLayout)
    {
        $components = collect();
        foreach ($internalLayout->getComponents() as $component) {
            // TODO Create or update related to layout
        }
        // TODO Remove all deprecated/unused components

        return $components;
    }
}