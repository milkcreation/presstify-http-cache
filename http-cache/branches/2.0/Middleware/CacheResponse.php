<?php declare(strict_types=1);

namespace tiFy\Plugins\HttpCache\Middleware;

use Psr\Http\Message\{ResponseInterface,ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface,RequestHandlerInterface};
use tiFy\Plugins\HttpCache\Contracts\ResponseCache;
use tiFy\Http\Request;
//use Zend\Diactoros\Response;

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
    public function process(ServerRequestInterface $psrRequest, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = Request::createFromPsr($psrRequest);

        $response = $handler->handle($psrRequest);

        var_dump($this->responseCache->getCachePath($psrRequest));

        exit;
    }
}