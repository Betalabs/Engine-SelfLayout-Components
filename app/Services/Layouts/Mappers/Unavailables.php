<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Layouts;


use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\AbstractMapper;
use Illuminate\Support\Facades\Config;

class Unavailables extends AbstractMapper
{
    /**
     * Map unavailable layouts and remove them from Engine API.
     */
    public function map()
    {
        $layouts = $this->parseLayouts();
        foreach ($layouts as $layout) {
            $this->deleteLayout($layout);
        }
    }

    /**
     * Call Engine API to delete layout instance.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Layout $layout
     */
    private function deleteLayout(Layout $layout)
    {
        // TODO
    }

    /**
     * Retrieve from all layouts configurations.
     *
     * @return array
     */
    protected function retrieveLayouts()
    {
        return Config::get('layouts.unavailable');
    }
}