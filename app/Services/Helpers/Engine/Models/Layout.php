<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models;


class Layout implements EngineModelInterface
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
     * @param int $id
     *
     * @return Layout
     */
    public function setId(int $id): Layout
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}