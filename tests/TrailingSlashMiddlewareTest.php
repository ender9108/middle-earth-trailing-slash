<?php
namespace Tests\EnderLab;

use EnderLab\Dispatcher\Dispatcher;
use EnderLab\TrailingSlashMiddleware;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class TrailingSlashMiddlewareTest extends TestCase
{
    public function testCreateMiddleware()
    {
        $middleware = new TrailingSlashMiddleware();
        $this->assertInstanceOf(TrailingSlashMiddleware::class, $middleware);
    }

    public function testWithGetRequest()
    {
        $request = new ServerRequest('GET', '/users/');
        $delegate = new Dispatcher();
        $middleware = new TrailingSlashMiddleware();
        $response = $middleware->process($request, $delegate);
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testWithPostRequest()
    {
        $request = new ServerRequest('POST', '/users/');
        $delegate = new Dispatcher();
        $middleware = new TrailingSlashMiddleware();
        $response = $middleware->process($request, $delegate);
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
