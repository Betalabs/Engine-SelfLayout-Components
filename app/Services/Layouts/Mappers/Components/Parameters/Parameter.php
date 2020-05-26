<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components\Parameters;


use Betalabs\EngineSelfLayoutComponents\Services\Layouts\AbstractMapperEntity;
use Illuminate\Support\Collection;

class Parameter extends AbstractMapperEntity
{
    /** @var string */
    private $name;
    /** @var string */
    private $label;
    /** @var string */
    private $description;
    /** @var \Illuminate\Support\Collection|string[] */
    private $possibleValues;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Parameter
     */
    public function setName(string $name): Parameter
    {
        $this->name = $name;

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
     * @return Parameter
     */
    public function setLabel(string $label): Parameter
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Parameter
     */
    public function setDescription(string $description): Parameter
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getPossibleValues(): \Illuminate\Support\Collection
    {
        return $this->possibleValues;
    }

    /**
     * @param \Illuminate\Support\Collection $possibleValues
     *
     * @return Parameter
     */
    public function setPossibleValues(\Illuminate\Support\Collection $possibleValues): Parameter
    {
        $this->possibleValues = $possibleValues;

        return $this;
    }
}