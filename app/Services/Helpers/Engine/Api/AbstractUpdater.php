<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api;


use Betalabs\LaravelHelper\Services\Engine\ResourceUpdater;

abstract class AbstractUpdater extends ResourceUpdater
{
    /**
     * @return mixed
     */
    public function update()
    {
        return $this->map(parent::update());
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
        return $data;
    }
}
