<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api;


use Betalabs\LaravelHelper\Services\Engine\AbstractShower as BaseAbstractShower;

class AbstractShower extends BaseAbstractShower
{
    /**
     * Retrieve a resource on engine
     *
     * @return mixed
     */
    public function retrieve()
    {
        return $this->map(parent::retrieve());
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