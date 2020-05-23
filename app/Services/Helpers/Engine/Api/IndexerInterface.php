<?php


namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api;


use Illuminate\Support\Collection;

interface IndexerInterface
{
    /**
     * Retrieve a resource
     *
     * @return \Illuminate\Support\Collection
     */
    public function index(): Collection;
}