<?php declare(strict_types=1);

namespace tiFy\Plugins\HttpCache\Contracts;

use DateTime;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};

interface ResponseCache
{
    /**
     * Vérifie si le système de mise en cache de la réponse HTTP est actif pour la requête fournie.
     *
     * @param ServerRequestInterface $request Instance de la requête HTTP PSR-7.
     *
     * @return boolean
     */
    public function enabled(ServerRequestInterface $request): bool;

    /**
     * Récupération d'un suffixe de qualification pour la requête HTTP fournie.
     * {@internal Permet de différencier les fichiers de stockage en cache.}
     *
     * @param ServerRequestInterface $request Instance de la requête HTTP PSR-7.
     *
     * @return mixed
     */
    public function cacheNameSuffix(ServerRequestInterface $request);

    /**
     * Récupére la date d'expiration d'une requête HTTP en cache.
     *
     * @param ServerRequestInterface $request Instance de la requête HTTP PSR-7.
     *
     * @return DateTime
     */
    public function cacheRequestUntil(ServerRequestInterface $request): DateTime;

    /**
     * Récupération du nom de qualification du fichier en cache.
     *
     * @param ServerRequestInterface $request Instance de la requête HTTP PSR-7.
     *
     * @return string
     */
    public function getCacheName(ServerRequestInterface $request): string;

    /**
     * Récupération du chemin de dépôt du fichier en cache.
     *
     * @param ServerRequestInterface $request Instance de la requête HTTP PSR-7.
     *
     * @return array
     */
    public function getCachePath(ServerRequestInterface $request): array;

    /**
     * Vérifie si la requête HTTP fournie doit être mise en cache.
     *
     * @param ServerRequestInterface $request Instance de la requête HTTP PSR-7.
     *
     * @return boolean
     */
    public function shouldCacheRequest(ServerRequestInterface $request): bool;

    /**
     * Vérifie si la réponse HTTP fournie doit être mise en cache.
     *
     * @param ResponseInterface $response Instance de la réponse HTTP PSR-7.
     *
     * @return boolean
     */
    public function shouldCacheResponse(ResponseInterface $response): bool;
}