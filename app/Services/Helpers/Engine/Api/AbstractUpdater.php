<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api;


use Betalabs\LaravelHelper\Services\Engine\AbstractUpdater as BaseAbstractUpdater;

abstract class AbstractUpdater extends BaseAbstractUpdater implements UpdaterInterface
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
     * @return \Illuminate\Support\Collection
     */
    protected function map($data)
    {
        return $data;
    }
}