<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Layouts;


class Layout extends AbstractMapperEntity
{
    /** @var string */
    private $name;
    /** @var string */
    private $alias;
    /** @var string */
    private $viewsPath;
    /** @var string */
    private $imagesPath;
    /** @var string */
    private $scriptsPath;
    /** @var string */
    private $stylesPath;
    /** @var string */
    private $fontsPath;
    /** @var string */
    private $videosPath;
    /** @var string */
    private $mainFile;
    /** @var \Illuminate\Support\Collection */
    private $components;
    /** @var \Illuminate\Support\Collection */
    private $colors;

    /**
     * Parse array data from layout packages configuration json files.
     *
     * @param array $data
     *
     * @return static
     */
    public static function fromArray(array $data)
    {
        $self = parent::fromArray($data);
        $self->setViewsPath($data['paths']['views'] ?? '');
        $self->setImagesPath($data['paths']['images'] ?? '');
        $self->setScriptsPath($data['paths']['scripts'] ?? '');
        $self->setStylesPath($data['paths']['styles'] ?? '');
        $self->setFontsPath($data['paths']['fonts'] ?? '');
        $self->setVideosPath($data['paths']['videos'] ?? '');

        return $self;
    }

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
     * @return Layout
     */
    public function setName(string $name): Layout
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     *
     * @return Layout
     */
    public function setAlias(string $alias): Layout
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @return string
     */
    public function getViewsPath(): string
    {
        return $this->viewsPath;
    }

    /**
     * @param string $viewsPath
     *
     * @return Layout
     */
    public function setViewsPath(string $viewsPath): Layout
    {
        $this->viewsPath = $viewsPath;

        return $this;
    }

    /**
     * @return string
     */
    public function getImagesPath(): string
    {
        return $this->imagesPath;
    }

    /**
     * @param string $imagesPath
     *
     * @return Layout
     */
    public function setImagesPath(string $imagesPath): Layout
    {
        $this->imagesPath = $imagesPath;

        return $this;
    }

    /**
     * @return string
     */
    public function getScriptsPath(): string
    {
        return $this->scriptsPath;
    }

    /**
     * @param string $scriptsPath
     *
     * @return Layout
     */
    public function setScriptsPath(string $scriptsPath): Layout
    {
        $this->scriptsPath = $scriptsPath;

        return $this;
    }

    /**
     * @return string
     */
    public function getStylesPath(): string
    {
        return $this->stylesPath;
    }

    /**
     * @param string $stylesPath
     *
     * @return Layout
     */
    public function setStylesPath(string $stylesPath): Layout
    {
        $this->stylesPath = $stylesPath;

        return $this;
    }

    /**
     * @return string
     */
    public function getFontsPath(): string
    {
        return $this->fontsPath;
    }

    /**
     * @param string $fontsPath
     *
     * @return Layout
     */
    public function setFontsPath(string $fontsPath): Layout
    {
        $this->fontsPath = $fontsPath;

        return $this;
    }

    /**
     * @return string
     */
    public function getVideosPath(): string
    {
        return $this->videosPath;
    }

    /**
     * @param string $videosPath
     *
     * @return Layout
     */
    public function setVideosPath(string $videosPath): Layout
    {
        $this->videosPath = $videosPath;

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
     * @return Layout
     */
    public function setMainFile(string $mainFile): Layout
    {
        $this->mainFile = $mainFile;

        return $this;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getComponents(): \Illuminate\Support\Collection
    {
        return $this->components;
    }

    /**
     * @param \Illuminate\Support\Collection $components
     *
     * @return Layout
     */
    public function setComponents(\Illuminate\Support\Collection $components): Layout
    {
        $this->components = $components;

        return $this;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getColors(): \Illuminate\Support\Collection
    {
        return $this->colors;
    }

    /**
     * @param \Illuminate\Support\Collection $colors
     *
     * @return Layout
     */
    public function setColors(\Illuminate\Support\Collection $colors): Layout
    {
        $this->colors = $colors;

        return $this;
    }
}