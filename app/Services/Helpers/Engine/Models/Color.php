<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models;


class Color implements EngineModelInterface
{
    /** @var integer */
    protected $id;

    public static function fromApiResponse($data)
    {
        $self = new self;
        $self->setId($data->id);

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
     * @return Color
     */
    public function setId(int $id): Color
    {
        $this->id = $id;

        return $this;
    }
}