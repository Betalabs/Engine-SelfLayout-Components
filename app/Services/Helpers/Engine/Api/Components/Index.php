<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Components;


use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\AbstractIndexer;
use Psr\Http\Message\ResponseInterface;

class Index extends AbstractIndexer
{
    /** @var Layout */
    protected $layout;

    /**
     * Return Engine endpoint
     *
     * @return string
     */
    protected function endpoint(): string
    {
        return '/api/layouts/'.$this->layout->getId().'/components';
    }

    /**
     * Handle request response
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    protected function handleResponse(ResponseInterface $response): void
    {
        // Do nothing...
    }
}