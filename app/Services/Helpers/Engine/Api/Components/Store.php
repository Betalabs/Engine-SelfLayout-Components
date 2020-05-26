<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components;


use Betalabs\EngineSelfLayoutComponents\Exceptions\app\Services\Helpers\Engine\Api\Components\LayoutIsNotDefinedException;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\AbstractCreator;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Component as EngineComponent;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout as EngineLayout;

class Store extends AbstractCreator
{
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout */
    protected $layout;

    /** @var string */
    protected $endpoint = '/api/layouts/{layoutId}/components';

    /**
     * Create resource on engine
     *
     * @return \Illuminate\Support\Collection|\Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Component
     */
    public function create()
    {
        if (null === $this->layout) {
            throw new LayoutIsNotDefinedException(
                'Layout is not defined in create components API service'
            );
        }

        $this->engineResourceCreator->setEndpointParameters([
            'layoutId' => $this->layout->getId()
        ]);

        return parent::create();
    }

    /**
     * Map response data.
     *
     * @param $data
     *
     * @return mixed
     */
    protected function map($data)
    {
        return EngineComponent::fromApiResponse($data);
    }

    /**
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout $layout
     *
     * @return Store
     */
    public function setLayout(EngineLayout $layout): Store
    {
        $this->layout = $layout;

        return $this;
    }
}