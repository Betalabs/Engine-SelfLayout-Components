<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models;


class Component implements EngineModelInterface
{
    /** @var integer */
    protected $id;

    public static function fromApiResponse($apiLayout)
    {
        $self = new self;
        $self->setId($apiLayout->id);

        return $self;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Component
     */
    public function setId(int $id): Component
    {
        $this->id = $id;

        return $this;
    }
}