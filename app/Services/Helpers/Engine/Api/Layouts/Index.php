<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts;


use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\AbstractIndexer;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout as EngineLayout;

class Index extends AbstractIndexer
{
    protected $endpoint = '/api/layouts';

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
            return EngineLayout::fromApiResponse($layout);
        });
    }
}