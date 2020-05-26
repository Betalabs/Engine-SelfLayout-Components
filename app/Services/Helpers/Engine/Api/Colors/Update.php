<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors;


use Betalabs\EngineSelfLayoutComponents\Exceptions\app\Services\Helpers\Engine\Api\Colors\LayoutIsNotDefinedException;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Color as EngineColor;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout as EngineLayout;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\AbstractUpdater;

class Update extends AbstractUpdater
{
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout */
    protected $layout;

    /** @var string */
    protected $endpoint = '/api/layouts/{layoutId}/colors';

    /**
     * @return mixed
     */
    public function update()
    {
        if (null === $this->layout) {
            throw new LayoutIsNotDefinedException(
                'Layout is not defined in update colors API service'
            );
        }

        $this->engineResourceUpdater->setEndpointParameters([
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
        return EngineColor::fromApiResponse($data);
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