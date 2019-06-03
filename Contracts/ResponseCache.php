<?php declare(strict_types=1);

namespace tiFy\Plugins\HttpCache\Contracts;

use Psr\Http\Message\{ResponseInterface as Response, ServerRequestInterface as Request};

interface ResponseCache
{
    /**
     * Récupération de l'instance du gestionnaire de cache.
     *
     * @return Cache
     */
    public function cache(): Cache;

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
     * {@internal Permet de différencier les élément en cache.}
     *
     * @param Request $request Instance de la requête HTTP PSR-7.
     *
     * @return mixed
     */
    public function cacheNameSuffix(Request $request);

    /**
     * Mise en cache de la réponse.
     *
     * @param Request $request Instance de la requête HTTP PSR-7.
     * @param Response $response Instance de la reponse HTTP PSR-7.
     * @param int $expire Nombre de seconde avant l'expiration de l'élément mis en cache.
     *
     * @return Response
     */
    public function cacheResponse(Request $request, Response $response, int $expire = 0): Response;

    /**
     * Récupération de la réponse en cache.
     *
     * @param Request $request Instance de la requête HTTP PSR-7.
     *
     * @return Response
     */
    public function getCacheResponse(Request $request): Response;

    /**
     * Vérification d'existance d'un élément en cache.
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