<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Colors;


use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Layout;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout as EngineLayout;

class Mapper
{
    public function map(EngineLayout $engineLayout, Layout $internalLayout)
    {
        $colors = collect();
        foreach ($internalLayout->getColors() as $color) {
            // TODO Create or update related to layout
        }
        // TODO Remove all deprecated/unused colors

        return $colors;
    }
}