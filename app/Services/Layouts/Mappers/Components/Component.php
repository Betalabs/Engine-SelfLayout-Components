<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components;


class Component
{
    /** @var string */
    private $name;
    /** @var string */
    private $description;
    /** @var string */
    private $path;
    /** @var string */
    private $mainFile;
    /** @var \Illuminate\Support\Collection */
    private $parameters;

    public static function fromArray(array $data)
    {
        // TODO
    }
}