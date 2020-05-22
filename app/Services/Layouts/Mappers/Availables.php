<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers;


use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Layout;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Colors\Mapper as ColorsMapper;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components\Mapper as ComponentsMapper;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout as EngineLayout;
use Illuminate\Support\Facades\Config;

class Availables extends AbstractMapper
{
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components\Mapper */
    private $componentsMapper;
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Colors\Mapper */
    private $colorsMapper;

    /**
     * Mapper constructor.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components\Mapper $componentsMapper
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Colors\Mapper     $colorsMapper
     */
    public function __construct(
        ComponentsMapper $componentsMapper,
        ColorsMapper $colorsMapper
    ) {
        $this->componentsMapper = $componentsMapper;
        $this->colorsMapper = $colorsMapper;
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
            $this->colorsMapper->map($engineLayout, $layout);
        }
    }

    /**
     * Send internal Layout class data to Engine API to register a single
     * Layout.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Layout $layout
     *
     * @return \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout
     */
    private function createLayout(Layout $layout)
    {
        return new EngineLayout();
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