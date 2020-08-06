<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components;


use Betalabs\EngineSelfLayoutComponents\Exceptions\app\Services\Helpers\Engine\Api\Components\LayoutIsNotDefinedException;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\AbstractShower;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Component as EngineComponent;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout as EngineLayout;

class Find extends AbstractShower
{
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout */
    protected $layout;

    protected $endpoint = '/api/layouts/{layoutId}/components';

    /**
     * @return mixed
     */
    public function retrieve()
    {
        if (null === $this->layout) {
            throw new LayoutIsNotDefinedException(
                'Layout is not defined in show components API service'
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
     * @return mixed
     */
    protected function map($data)
    {
        return EngineComponent::fromApiResponse($data);
    }

    /**
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout $layout
     *
     * @return Find
     */
    public function setLayout(EngineLayout $layout): Find
    {
        $this->layout = $layout;

        return $this;
    }
}
