<?php declare(strict_types=1);

namespace tiFy\Plugins\HttpCache;

use DateTime;
use Minify_HTML;
use Psr\Http\Message\{ResponseInterface as Response, ServerRequestInterface as Request};
use tiFy\Http\Response as HttpResponse;
use tiFy\Contracts\Filesystem\Filesystem;
use tiFy\Plugins\HttpCache\Contracts\ResponseCache as ResponseCacheContract;

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
    public function cacheNameSuffix(Request $request)
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function cacheRequestUntil(Request $request): DateTime
    {
        return new DateTime();
    }

    /**
     * @inheritDoc
     */
    public function cacheResponse(Request $request, Response $response, $lifetimeInSeconds = null): Response
    {
        /*if (config('responsecache.add_cache_time_header')) {
            $response = $this->addCachedHeader($response);
        }
        $lifetimeInSeconds = $lifetimeInSeconds
            ? (int)$lifetimeInSeconds
            : $this->cacheProfile->cacheRequestUntil($request);*/
        $content = (string)$response->getBody();
        $content = Minify_HTML::minify($content);

        $this->disk()->put($this->getCachePath($request), $content);

        return $response;
    }

    /**
     * @inheritDoc
     */
    public function disk(): Filesystem
    {
        return $this->manager->cache();
    }

    /**
     * @inheritDoc
     */
    public function enabled(Request $request): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getCacheName(Request $request): string
    {
        $hash = $request->getUri()->getHost() . $request->getUri()->getPath();
        $hash .= ($query = $request->getUri()->getQuery()) ? "?{$query}" : '';
        $hash .= $this->cacheNameSuffix($request);

        return md5($hash);
    }

    /**
     * @inheritDoc
     */
    public function getCachePath(Request $request): string
    {
        return implode('/', $this->getCachePathSegments($request));
    }

    /**
     * @inheritDoc
     */
    public function getCachePathSegments(Request $request): array
    {
        $segments = preg_split('#\/#', $request->getUri()->getPath(), 0, PREG_SPLIT_NO_EMPTY) ?: [];
        array_unshift($segments, $request->getUri()->getHost());
        $segments[] = $this->getCacheName($request);

        return $segments;
    }

    /**
     * @inheritDoc
     */
    public function getCacheResponse(Request $request): Response
    {
        $path = $this->getCachePath($request);
        $response = $this->disk()->binary($path);

        return HttpResponse::convertToPsr($response);
    }

    /**
     * @inheritDoc
     */
    public function hasCache(Request $request): bool
    {
        $path = $this->getCachePath($request);

        return $this->disk()->has($path);
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