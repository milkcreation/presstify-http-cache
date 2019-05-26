<?php declare(strict_types=1);

namespace tiFy\Plugins\HttpCache\Contracts;

use DateTime;
use League\Flysystem\FileNotFoundException;
use Psr\Http\Message\{ResponseInterface as Response, ServerRequestInterface as Request};
use tiFy\Contracts\Filesystem\Filesystem;

interface ResponseCache
{
    /**
     * Récupération de l'instance du gestionnaire de fichier en cache.
     *
     * @return Filesystem
     */
    public function disk(): Filesystem;

    /**
     * Vérifie si le système de mise en cache de la réponse HTTP est actif pour la requête fournie.
     *
     * @param Request $request Instance de la requête HTTP PSR-7.
     *
     * @return boolean
     */
    public function enabled(Request $request): bool;

    /**
     * Récupération d'un suffixe de qualification pour la requête HTTP fournie.
     * {@internal Permet de différencier les fichiers de stockage en cache.}
     *
     * @param Request $request Instance de la requête HTTP PSR-7.
     *
     * @return mixed
     */
    public function cacheNameSuffix(Request $request);

    /**
     * Récupére la date d'expiration d'une requête HTTP en cache.
     *
     * @param Request $request Instance de la requête HTTP PSR-7.
     *
     * @return DateTime
     */
    public function cacheRequestUntil(Request $request): DateTime;

    /**
     * Mise en cache de la réponse.
     *
     * @param Request $request Instance de la requête HTTP PSR-7.
     * @param Response $response Instance de la reponse HTTP PSR-7.
     * @param DateTime $expires Date d'expiration de la mise en cache.
     *
     * @return Response
     */
    public function cacheResponse(Request $request, Response $response, $expires = null): Response;

    /**
     * Récupération du nom de qualification du fichier en cache.
     *
     * @param Request $request Instance de la requête HTTP PSR-7.
     *
     * @return string
     */
    public function getCacheName(Request $request): string;

    /**
     * Récupération du chemin de dépôt du fichier en cache.
     *
     * @param Request $request Instance de la requête HTTP PSR-7.
     *
     * @return string
     */
    public function getCachePath(Request $request): string;

    /**
     * Récupération des parties du chemin vers le fichier en cache.
     *
     * @param Request $request Instance de la requête HTTP PSR-7.
     *
     * @return array
     */
    public function getCachePathSegments(Request $request): array;

    /**
     * Récupération de la réponse en cache.
     *
     * @param Request $request Instance de la requête HTTP PSR-7.
     *
     * @return Response
     *
     * @throws FileNotFoundException
     */
    public function getCacheResponse(Request $request): Response;

    /**
     * Vérification d'existance du fichier en cache.
     *
     * @param Request $request Instance de la requête HTTP PSR-7.
     *
     * @return boolean
     */
    public function hasCache(Request $request): bool;

    /**
     * Vérifie si la mise en cache doit être faite.
     *
     * @param Request $request Instance de la requête HTTP PSR-7.
     * @param Response $response Instance de la réponse HTTP PSR-7.
     *
     * @return bool
     */
    public function shouldCache(Request $request, Response $response): bool;

    /**
     * Vérifie si la requête HTTP fournie doit être mise en cache.
     *
     * @param Request $request Instance de la requête HTTP PSR-7.
     *
     * @return boolean
     */
    public function shouldCacheRequest(Request $request): bool;

    /**
     * Vérifie si la réponse HTTP fournie doit être mise en cache.
     *
     * @param Response $response Instance de la réponse HTTP PSR-7.
     *
     * @return boolean
     */
    public function shouldCacheResponse(Response $response): bool;
}