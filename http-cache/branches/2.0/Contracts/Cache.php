<?php declare(strict_types=1);

namespace tiFy\Plugins\HttpCache\Contracts;

use Psr\Http\Message\ServerRequestInterface as Request;

interface Cache
{
    /**
     * Récupération de l'élément en cache en correspondance avec l'indice de qualification fourni.
     *
     * @param string $key Indice de qualification.
     *
     * @return string
     */
    public function contents($key): string;

    /**
     * Vérification d'existance d'un éléments en cache en correspondance avec l'indice de qualification fourni.
     *
     * @param string $key Indice de qualification.
     *
     * @return bool
     */
    public function exists($key): bool;

    /**
     * Suppression de tous les éléments en cache.
     *
     * @return boolean
     */
    public function flush(): bool;

    /**
     * Suppression de l'élément en cache en correspondance avec l'indice de qualification fourni.
     *
     * @param string $key Indice de qualification.
     *
     * @return boolean
     */
    public function forget($key): bool;

    /**
     * Récupération de l'indice de qualification de l'élément en cache en correspondance avec la requête HTTP.
     *
     * @param Request $request Instance de la requête HTTP PSR-7.
     *
     * @return string
     */
    public function hash(Request $request): string;

    /**
     * Mise en cache de l'élément en correspondance avec l'indice de qualification fourni.
     *
     * @param string $key Indice de qualification.
     * @param mixed $value Valeur de l'élément à mettre en cache.
     * @param int $expire Nombre de seconde jusqu'à l'expiration de l'élément en cache.
     *
     * @return mixed
     */
    public function store($key, $value, $expire);
}