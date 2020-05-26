<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors;


use Betalabs\EngineSelfLayoutComponents\Exceptions\app\Services\Helpers\Engine\Api\Colors\LayoutIsNotDefinedException;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\AbstractCreator;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Color as EngineColor;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout as EngineLayout;

class Store extends AbstractCreator
{
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout */
    protected $layout;

    /** @var string */
    protected $endpoint = '/api/layouts/{layoutId}/colors';

    /**
     * Create resource on engine
     *
     * @return \Illuminate\Support\Collection|\Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Color
     */
    public function create()
    {
        if (null === $this->layout) {
            throw new LayoutIsNotDefinedException(
                'Layout is not defined in create colors API service'
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
        return EngineColor::fromApiResponse($data);
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