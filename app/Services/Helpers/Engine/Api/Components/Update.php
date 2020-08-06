<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components;


use Betalabs\EngineSelfLayoutComponents\Exceptions\app\Services\Helpers\Engine\Api\Components\LayoutIsNotDefinedException;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Component as EngineComponent;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout as EngineLayout;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\AbstractUpdater;

class Update extends AbstractUpdater
{
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout */
    protected $layout;

    /** @var string */
    protected $endpoint = '/api/layouts/{layoutId}/components';

    /**
     * @return mixed
     */
    public function update()
    {
        if (null === $this->layout) {
            throw new LayoutIsNotDefinedException(
                'Layout is not defined in update components API service'
            );
        }

        $this->setEndpointParameters([
            'layoutId' => $this->layout->getId()
        ]);

        return parent::update();
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
     * @return Update
     */
    public function setLayout(EngineLayout $layout): Update
    {
        $this->layout = $layout;

        return $this;
    }

}
