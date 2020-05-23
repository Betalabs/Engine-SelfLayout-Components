<?php


namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api;


use Betalabs\LaravelHelper\Services\Engine\AbstractUpdater;

interface UpdaterInterface
{
    /**
     * Call API to perform resource update.
     *
     * @return mixed
     */
    public function update();

    /**
     * @param int $recordId
     * @return AbstractUpdater
     */
    public function setRecordId(int $recordId): AbstractUpdater;
}