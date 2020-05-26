<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors;


use Betalabs\EngineSelfLayoutComponents\Exceptions\app\Services\Helpers\Engine\Api\Colors\LayoutIsNotDefinedException;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\AbstractDestroyer;

class Destroy extends AbstractDestroyer
{
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout */
    protected $layout;

    protected $endpoint = '/api/layouts/{layoutId}/colors';

    /**
     * Perform resource destroy.
     *
     * @return null
     */
    public function destroy()
    {
        if (null === $this->layout) {
            throw new LayoutIsNotDefinedException(
                'Layout is not defined in destroy colors API service'
            );
        }

        $this->setEndpointParameters([
            'layoutId' => $this->layout->getId()
        ]);

        return parent::destroy();
    }

    /**
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout $layout
     *
     * @return Destroy
     */
    public function setLayout(\Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout $layout): Destroy
    {
        $this->layout = $layout;

        return $this;
    }
}