<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers;


use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Layout;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components\Mapper as ComponentsMapper;
use Illuminate\Support\Facades\Config;

class Availables extends AbstractMapper
{
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components\Mapper */
    private $componentsMapper;

    /**
     * Mapper constructor.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components\Mapper $componentsMapper
     */
    public function __construct(ComponentsMapper $componentsMapper)
    {
        $this->componentsMapper = $componentsMapper;
    }

    /**
     * Map available layouts and send to Engine API.
     */
    public function map()
    {
        $layouts = $this->parseLayouts();
        foreach ($layouts as $layout) {
            $engineLayout = $this->createLayout($layout);

            $this->componentsMapper->map($engineLayout, $layout);
        }
    }

    /**
     * Send internal Layout class data to Engine API to register a single
     * Layout.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Layout $layout
     */
    private function createLayout(Layout $layout)
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
        return Config::get('layouts.available');
    }
}