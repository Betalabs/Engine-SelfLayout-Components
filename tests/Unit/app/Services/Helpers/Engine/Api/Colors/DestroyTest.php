<?php

namespace Betalabs\EngineSelfLayoutComponents\Tests\Unit\app\Services\Helpers\Engine\Api\Colors;


use Betalabs\Engine\Request as EngineRequestFactory;
use Betalabs\Engine\Requests\Methods\Delete as EngineDeleteMethod;
use Betalabs\EngineSelfLayoutComponents\Exceptions\app\Services\Helpers\Engine\Api\Colors\LayoutIsNotDefinedException;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Api\Colors\Destroy;
use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine\Models\Layout;
use Betalabs\EngineSelfLayoutComponents\Tests\TestCase;
use Illuminate\Http\Response;
use Psr\Http\Message\ResponseInterface;

class DestroyTest extends TestCase
{
    public function testDestroyShouldIncludeLayoutIdIntoRouteParameters()
    {
        $layout = \Mockery::mock(Layout::class);
        $layout->shouldReceive('getId')->andReturn(123);

        $response = \Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getStatusCode')->andReturn(Response::HTTP_NO_CONTENT);

        $engineDeleteMethod = \Mockery::mock(EngineDeleteMethod::class);
        $engineDeleteMethod->shouldReceive('send')
            ->once()
            ->withArgs([
                '/api/layouts/123/colors/321', []
            ]);
        $engineDeleteMethod->shouldReceive('getResponse')
            ->once()
            ->andReturn($response);

        $engineRequestFactory = \Mockery::mock(EngineRequestFactory::class);
        $engineRequestFactory->shouldReceive('delete')
            ->once()
            ->andReturn($engineDeleteMethod);

        $destroyer = new Destroy($engineRequestFactory);
        $destroyer->setLayout($layout)->setRecordId(321)->destroy();
    }

    public function testDestroyWithoutSetLayoutShouldThrowException()
    {
        $engineDeleteMethod = \Mockery::mock(EngineDeleteMethod::class);
        $engineDeleteMethod->shouldReceive('send')->never();

        $engineRequestFactory = \Mockery::mock(EngineRequestFactory::class);
        $engineRequestFactory->shouldReceive('delete')->never();

        $this->expectException(LayoutIsNotDefinedException::class);

        $destroyer = new Destroy($engineRequestFactory);
        $destroyer->setRecordId(321)->destroy();
    }

    public function testDestroyShouldReturnNullFromNoContentResponse()
    {
        $layout = \Mockery::mock(Layout::class);
        $layout->shouldReceive('getId')->andReturn(123);

        $response = \Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getStatusCode')->andReturn(Response::HTTP_NO_CONTENT);

        $engineDeleteMethod = \Mockery::mock(EngineDeleteMethod::class);
        $engineDeleteMethod->shouldReceive('send')
            ->once()
            ->with('/api/layouts/123/colors/321', []);
        $engineDeleteMethod->shouldReceive('getResponse')
            ->once()
            ->andReturn($response);

        $engineRequestFactory = \Mockery::mock(EngineRequestFactory::class);
        $engineRequestFactory->shouldReceive('delete')
            ->once()
            ->andReturn($engineDeleteMethod);

        $destroyer = new Destroy($engineRequestFactory);
        $result = $destroyer->setLayout($layout)->setRecordId(321)->destroy();

        $this->assertEquals(null, $result);
    }

    public function testDestroyShouldThrowRuntimeExceptionForErrorStatusCode()
    {
        $layout = \Mockery::mock(Layout::class);
        $layout->shouldReceive('getId')->andReturn(123);

        $response = \Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getStatusCode')->andReturn(Response::HTTP_NOT_FOUND);

        $engineDeleteMethod = \Mockery::mock(EngineDeleteMethod::class);
        $engineDeleteMethod->shouldReceive('send')
            ->once()
            ->with('/api/layouts/123/colors/321', []);
        $engineDeleteMethod->shouldReceive('getResponse')
            ->once()
            ->andReturn($response);

        $engineRequestFactory = \Mockery::mock(EngineRequestFactory::class);
        $engineRequestFactory->shouldReceive('delete')
            ->once()
            ->andReturn($engineDeleteMethod);

        $this->expectException(\RuntimeException::class);

        $destroyer = new Destroy($engineRequestFactory);
        $destroyer->setLayout($layout)->setRecordId(321)->destroy();
    }
}