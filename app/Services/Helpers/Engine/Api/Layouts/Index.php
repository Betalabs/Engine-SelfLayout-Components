<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Layouts;


use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\AbstractIndexer;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout as EngineLayout;
use Illuminate\Support\Collection;

class Index extends AbstractIndexer
{
    /**
     * Retrieve a resource
     *
     * @return \Illuminate\Support\Collection
     */
    public function index(): Collection
    {
        $this->setEndpoint('/api/layouts');
        return parent::index();
    }

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