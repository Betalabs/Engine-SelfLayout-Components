<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mappers\Components;


use Betalabs\EngineSelfLayoutComponents\Services\Layouts\AbstractMapperEntity;
use Illuminate\Support\Collection;

class Component extends AbstractMapperEntity
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
     * @return Component
     */
    public function setName(string $name): Component
    {
        $this->name = $name;

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
     * @return Component
     */
    public function setDescription(string $description): Component
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return Component
     */
    public function setPath(string $path): Component
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getMainFile(): string
    {
        return $this->mainFile;
    }

    /**
     * @param string $mainFile
     *
     * @return Component
     */
    public function setMainFile(string $mainFile): Component
    {
        $this->mainFile = $mainFile;

        return $this;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getParameters(): Collection
    {
        return $this->parameters;
    }

    /**
     * @param \Illuminate\Support\Collection $parameters
     *
     * @return Component
     */
    public function setParameters(Collection $parameters): Component
    {
        $this->parameters = $parameters;

        return $this;
    }
}