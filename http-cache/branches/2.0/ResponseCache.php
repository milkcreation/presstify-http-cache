<?php declare(strict_types=1);

namespace tiFy\Plugins\HttpCache;

use tiFy\Http\Response as HttpResponse;
use voku\helper\HtmlMin;
use Psr\Http\Message\{ResponseInterface as Response, ServerRequestInterface as Request};
use tiFy\Plugins\HttpCache\Contracts\{Cache, ResponseCache as ResponseCacheContract};

class ResponseCache implements ResponseCacheContract
{
    /**
     * Instance du gestionnaire de cache HTTP.
     * @return HttpCache
     */
    protected $manager;

    /**
     * CONSTRUCTEUR.
     *
     * @param HttpCache $manager Instance du gestionnaire de cache HTTP.
     *
     * @return void
     */
    public function __construct(HttpCache $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @inheritDoc
     */
    public function cache(): Cache
    {
        return $this->manager->cache();
    }

    /**
     * @inheritDoc
     */
    public function cacheNameSuffix(Request $request)
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function cacheResponse(Request $request, Response $response, ?int $expire = 0): Response
    {
        /*
        if (config('responsecache.add_cache_time_header')) {
            $response = $this->addCachedHeader($response);
        } */

        $expire = $expire ?: $this->config('expire', 3600);

        $contents = (string)$response->getBody();
        if ($this->config()->get('minify', true)) {
            $contents = (new HtmlMin())->minify($contents);
        }

        if ($this->compressed()) {
            $contents = gzcompress($contents);
        }

        $key = $this->cache()->hash($request);

        $this->cache()->store($key, $contents, $expire);

        return $response;
    }

    /**
     * @inheritDoc
     */
    public function compressed(): bool
    {
        return $this->config('compress', true) && extension_loaded('zlib');
    }

    /**
     * @inheritDoc
     */
    public function config($key = null, $default = null)
    {
        return $this->manager->config($key, $default);
    }

    /**
     * @inheritDoc
     */
    public function enabled(Request $request): bool
    {
        return $this->config()->get('enabled', true);
    }

    /**
     * @inheritDoc
     */
    public function getCacheResponse(Request $request): Response
    {
        $path = $this->cache()->hash($request);
        $contents = $this->cache()->contents($path);

        if ($this->compressed()) {
            $contents = gzuncompress($contents);
        }

        return HttpResponse::convertToPsr(new HttpResponse($contents));
    }

    /**
     * @inheritDoc
     */
    public function hasCache(Request $request): bool
    {
        $path = $this->cache()->hash($request);

        return $this->cache()->exists($path);
    }

    /**
     * @inheritDoc
     */
    public function shouldCache(Request $request, Response $response): bool
    {
        /*if ($request->attributes->has('responsecache.doNotCache')) {
            return false;
        }*/

        if (!$this->shouldCacheRequest($request)) {
            return false;
        }

        return $this->shouldCacheResponse($response);
    }

    /**
     * @inheritDoc
     */
    public function shouldCacheRequest(Request $request): bool
    {
        if (in_array('XMLHttpRequest', $request->getHeader('X-Requested-With'))) {
            return false;
        }

        return $request->getMethod() === 'GET';
    }

    /**
     * @inheritDoc
     */
    public function shouldCacheResponse(Response $response): bool
    {
        $contentType = $response->getHeader('Content-Type');

        if (!preg_match('#^text#', $contentType[0] ?? '')) {
            return false;
        }

        $statusCode = $response->getStatusCode();
        if ($statusCode >= 200 && $statusCode < 300) {
            return true;
        } elseif ($statusCode >= 300 && $statusCode < 400) {
            return true;
        }

        return false;
    }
}