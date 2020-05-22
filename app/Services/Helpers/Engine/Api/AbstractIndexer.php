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
        $this->engineResourceIndexer->setOffset($this->offset);
        $data = parent::index();

        return $this->map($data);
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