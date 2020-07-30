<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api;


use Betalabs\LaravelHelper\Services\Engine\ResourceCreator;

abstract class AbstractCreator extends ResourceCreator
{
    /**
     * Create resource on engine
     *
     * @return mixed
     */
    public function create()
    {
        return $this->map(parent::create());
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
