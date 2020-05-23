<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Colors;


use Betalabs\EngineSelfLayoutComponents\Services\Layouts\AbstractMapperEntity;

class Color extends AbstractMapperEntity
{
    /** @var string */
    private $identification;
    /** @var string */
    private $label;
    /** @var boolean */
    private $default;

    /**
     * @return string
     */
    public function getIdentification(): string
    {
        return $this->identification;
    }

    /**
     * @param string $identification
     *
     * @return Color
     */
    public function setIdentification(string $identification): Color
    {
        $this->identification = $identification;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return Color
     */
    public function setLabel(string $label): Color
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->default;
    }

    /**
     * @param bool $default
     *
     * @return Color
     */
    public function setDefault(bool $default): Color
    {
        $this->default = $default;

        return $this;
    }
}