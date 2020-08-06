<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components;


use Betalabs\EngineSelfLayoutComponents\Exceptions\app\Services\Helpers\Engine\Api\Components\LayoutIsNotDefinedException;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\AbstractIndexer;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Component as EngineComponent;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout as EngineLayout;
use Illuminate\Support\Collection;

class Index extends AbstractIndexer
{
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout */
    protected $layout;

    /** @var string */
    protected $endpoint = '/api/layouts/{layoutId}/components';

    /**
     * Retrieve a resource
     *
     * @return \Illuminate\Support\Collection
     */
    public function retrieve(): Collection
    {
        if (null === $this->layout) {
            throw new LayoutIsNotDefinedException(
                'Layout is not defined in index components API service'
            );
        }

        $this->setEndpointParameters([
            'layoutId' => $this->layout->getId()
        ]);

        return parent::retrieve();
    }

    /**
     * Map response data.
     *
     * @param $data
     *
     * @return \Illuminate\Support\Collection
     */
    protected function map($data)
    {
        return parent::map($data)->map(function ($layout) {
            return EngineComponent::fromApiResponse($layout);
        });
    }

    /**
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout $layout
     *
     * @return \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components\Index
     */
    public function setLayout(EngineLayout $layout): Index
    {
        $this->layout = $layout;

        return $this;
    }
}
