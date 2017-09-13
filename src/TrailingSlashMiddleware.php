<?php

namespace EnderLab;

use GuzzleHttp\Psr7\Response;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TrailingSlashMiddleware implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $path = (string) $request->getUri()->getPath();

        if ('/' !== $path && '/' === mb_substr($path, -1)) {
            $uri = $request->getUri()->withPath(mb_substr($path, 0, -1));

            if ('GET' === $request->getMethod()) {
                return (new Response())
                    ->withHeader('Location', (string) $uri)
                    ->withStatus(301);
            }

            return $delegate->process($request->withUri($uri));
        }

        return $delegate->process($request);
    }
}
