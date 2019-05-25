<?php declare(strict_types=1);

namespace tiFy\Plugins\HttpCache;

use DateTime;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use tiFy\Plugins\HttpCache\Contracts\ResponseCache as ResponseCacheContract;

class ResponseCache implements ResponseCacheContract
{
    /**
     * @inheritDoc
     */
    public function cacheNameSuffix(ServerRequestInterface $request)
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function cacheRequestUntil(ServerRequestInterface $request): DateTime
    {
        return new DateTime();
    }

    /**
     * @inheritDoc
     */
    public function enabled(ServerRequestInterface $request): bool
    {
        return true;
    }

    /**
     * Récupération du nom de qualification du fichier en cache.
     *
     * @param ServerRequestInterface $request Instance de la requête HTTP PSR-7.
     *
     * @return string
     */
    public function getCacheName(ServerRequestInterface $request): string
    {
        $hash = $request->getUri()->getHost() . $request->getUri()->getPath();
        $hash .= ($query = $request->getUri()->getQuery()) ? "?{$query}" : '';
        $hash .= $this->cacheNameSuffix($request);

        return md5($hash);
    }

    /**
     * Récupération du chemin de dépôt du fichier en cache.
     *
     * @param ServerRequestInterface $request Instance de la requête HTTP PSR-7.
     *
     * @return array
     */
    public function getCachePath(ServerRequestInterface $request): array
    {
        $path = preg_split('#\/#', $request->getUri()->getPath(), 0, PREG_SPLIT_NO_EMPTY) ? : [];
        array_unshift($path, $request->getUri()->getHost());
        $path[] = $this->getCacheName($request);

        return $path;
    }

    /**
     * @inheritDoc
     */
    public function shouldCacheRequest(ServerRequestInterface $request): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function shouldCacheResponse(ResponseInterface $response): bool
    {
        return true;
    }
}