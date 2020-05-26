<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts;


use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\AbstractShower;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout as EngineLayout;

class Find extends AbstractShower
{
    protected $endpoint = '/api/layouts';

    /**
     * Map response data.
     *
     * @param $data
     *
     * @return mixed
     */
    protected function map($data)
    {
        return EngineLayout::fromApiResponse($data);
    }
}