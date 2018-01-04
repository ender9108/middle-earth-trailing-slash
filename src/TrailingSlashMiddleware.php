<?php

namespace EnderLab;

use GuzzleHttp\Psr7\Response;
use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TrailingSlashMiddleware implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $requestHandler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $requestHandler): ResponseInterface
    {
        $path = (string) $request->getUri()->getPath();

        if ('/' !== $path && '/' === mb_substr($path, -1)) {
            $uri = $request->getUri()->withPath(mb_substr($path, 0, -1));

            if ('GET' === $request->getMethod()) {
                return (new Response())
                    ->withHeader('Location', (string) $uri)
                    ->withStatus(301);
            }

            return $requestHandler->handle($request->withUri($uri));
        }

        return $requestHandler
            ->handle($request);
    }
}
