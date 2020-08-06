<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components;


use Betalabs\EngineSelfLayoutComponents\Exceptions\app\Services\Helpers\Engine\Api\Components\LayoutIsNotDefinedException;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\AbstractDestroyer;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout;

class Destroy extends AbstractDestroyer
{
    /** @var \Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout */
    protected $layout;

    protected $endpoint = '/api/layouts/{layoutId}/components';

    /**
     * Perform resource destroy.
     *
     * @return null
     */
    public function destroy()
    {
        if (null === $this->layout) {
            throw new LayoutIsNotDefinedException(
                'Layout is not defined in destroy components API service'
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
    public function setLayout(Layout $layout): Destroy
    {
        $this->layout = $layout;

        return $this;
    }
}
