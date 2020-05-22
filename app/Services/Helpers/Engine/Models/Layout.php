<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models;


class Layout implements EngineModelInterface
{
    /** @var integer */
    protected $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}