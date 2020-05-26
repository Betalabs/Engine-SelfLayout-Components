<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors;


use Betalabs\EngineSelfLayoutComponents\Exceptions\app\Services\Helpers\Engine\Api\Colors\LayoutIsNotDefinedException;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\AbstractShower;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Color as EngineColor;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout as EngineLayout;

class Find extends AbstractShower
{
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout */
    protected $layout;

    protected $endpoint = '/api/layouts/{layoutId}/colors';

    /**
     * @return mixed
     */
    public function retrieve()
    {
        if (null === $this->layout) {
            throw new LayoutIsNotDefinedException(
                'Layout is not defined in show colors API service'
            );
        }

        $this->engineResourceShower->setEndpointParameters([
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
        return EngineColor::fromApiResponse($data);
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