<?php declare(strict_types=1);

namespace tiFy\Plugins\HttpCache\Contracts;

use Psr\Http\Message\ServerRequestInterface as Request;
use tiFy\Contracts\Filesystem\Filesystem;

interface CacheStatic extends Cache, Filesystem
{
    /**
     * Récupération des parties du chemin vers le fichier en cache.
     *
     * @param Request $request Instance de la requête HTTP PSR-7.
     *
     * @return array
     */
    public function segments(Request $request): array;
}