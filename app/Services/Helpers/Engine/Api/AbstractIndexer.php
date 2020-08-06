<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api;


use Betalabs\LaravelHelper\Services\Engine\ResourceIndexer;
use Illuminate\Support\Collection;

abstract class AbstractIndexer extends ResourceIndexer
{
    /**
     * Retrieve a resource
     *
     * @return \Illuminate\Support\Collection
     */
    public function retrieve(): Collection
    {
        $this->prepareOffset();
        $data = parent::retrieve();

        return $this->map($data);
    }

    /**
     * Prepare offset into resource if defined.
     */
    private function prepareOffset()
    {
        if (null !== $this->offset) {
            $this->setOffset($this->offset);
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
