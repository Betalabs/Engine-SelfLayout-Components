<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api;


use Betalabs\LaravelHelper\Services\Engine\AbstractIndexer as BaseAbstractIndexer;
use Illuminate\Support\Collection;

abstract class AbstractIndexer extends BaseAbstractIndexer implements IndexerInterface
{
    /**
     * Retrieve a resource
     *
     * @return \Illuminate\Support\Collection
     */
    public function index(): Collection
    {
        $this->prepareOffset();
        $data = parent::index();

        return $this->map($data);
    }

    /**
     * Prepare offset into resource if defined.
     */
    private function prepareOffset()
    {
        if (null !== $this->offset) {
            $this->engineResourceIndexer->setOffset($this->offset);
        }
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
        return $data;
    }
}