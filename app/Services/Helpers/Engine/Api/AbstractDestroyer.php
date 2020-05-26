<?php

namespace Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api;


use Betalabs\Engine\Request;
use Betalabs\LaravelHelper\Services\Engine\ReplacesEndpointParameters;
use Illuminate\Http\Response;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractDestroyer
{
    use ReplacesEndpointParameters;

    /**
     * @var string
     */
    protected $endpoint;
    /**
     * @var array
     */
    protected $endpointParameters = [];
    /**
     * @var int
     */
    protected $recordId;
    /**
     * @var string string
     */
    protected $exceptionMessage = 'Resource could not be destroyed.';

    /**
     * Perform resource destroy.
     *
     * @return null
     */
    public function destroy()
    {
        $request = Request::delete();

        $this->replaceEndpointParameters();
        $recordId = $this->recordId ?? "";

        $request->send("{$this->endpoint}/{$recordId}");
        $this->errors($request->getResponse());

        return null;
    }

    /**
     * Handle request response
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    protected function errors(ResponseInterface $response): void
    {
        if ($response->getStatusCode() != Response::HTTP_NO_CONTENT) {
            throw new \RuntimeException($this->exceptionMessage);
        }
    }

    /**
     * @param string $endpoint
     *
     * @return AbstractDestroyer
     */
    public function setEndpoint(string $endpoint): AbstractDestroyer
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * @param array $endpointParameters
     *
     * @return AbstractDestroyer
     */
    public function setEndpointParameters(array $endpointParameters): AbstractDestroyer
    {
        $this->endpointParameters = $endpointParameters;

        return $this;
    }

    /**
     * @param int $recordId
     *
     * @return AbstractDestroyer
     */
    public function setRecordId(int $recordId): AbstractDestroyer
    {
        $this->recordId = $recordId;

        return $this;
    }
}