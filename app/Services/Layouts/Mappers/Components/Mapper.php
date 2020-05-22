<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components;


use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Layout;

class Mapper
{
    public function map($engineLayout, Layout $internalLayout)
    {
        $components = collect();
        foreach ($internalLayout->getComponents() as $component) {

        }

        return $components;
    }
}