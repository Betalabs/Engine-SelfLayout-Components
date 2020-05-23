<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api;


use Betalabs\LaravelHelper\Services\Engine\AbstractCreator as BaseAbstractCreator;

abstract class AbstractCreator extends BaseAbstractCreator implements CreatorInterface
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
     * @return \Illuminate\Support\Collection
     */
    protected function map($data)
    {
        return $data;
    }
}