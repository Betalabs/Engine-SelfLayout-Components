<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Layouts;


class Layout
{
    /** @var \Illuminate\Support\Collection */
    private $components;

    /**
     * Parse json from layout packages configuration json files.
     *
     * @param $json
     *
     * @return self
     */
    public static function fromJson(\stdClass $json)
    {
        $instance = new self;
        return $instance;
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
}