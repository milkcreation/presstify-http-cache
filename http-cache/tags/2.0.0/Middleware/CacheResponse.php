<?php declare(strict_types=1);

namespace tiFy\Plugins\HttpCache\Middleware;

use Psr\Http\Message\{ResponseInterface,ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface,RequestHandlerInterface};
use tiFy\Plugins\HttpCache\Contracts\ResponseCache;

class CacheResponse implements MiddlewareInterface
{
    /**
     * Instance du gestionnaire de cache de la réponse HTTP.
     * @var ResponseCache
     */
    protected $responseCache;

    /**
     * CONSTRUCTEUR.
     *
     * @param ResponseCache $responseCache Instance du gestionnaire de cache de la réponse HTTP.
     *
     * @return void
     */
    public function __construct(ResponseCache $responseCache)
    {
        $this->responseCache = $responseCache;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->responseCache->enabled($request)) {
            if ($this->responseCache->hasCache($request)) {
                return $this->responseCache->getCacheResponse($request);
            }
        }
        $response = $handler->handle($request);

        events()->listen('router.emit.response', function (ResponseInterface $response) use ($request) {
            if ($response->getBody()->getSize()) {
                if ($this->responseCache->enabled($request)) {
                    if ($this->responseCache->shouldCache($request, $response)) {
                        $this->responseCache->cacheResponse($request, $response);
                    }
                }
            }
        });

        return $response;
    }
}