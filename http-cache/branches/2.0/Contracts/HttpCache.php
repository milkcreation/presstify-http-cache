<?php declare(strict_types=1);

namespace tiFy\Plugins\HttpCache\Contracts;

use tiFy\Contracts\Container\Container;

interface HttpCache
{
    /**
     * Récupération de l'instance du gestionnaire de cache.
     *
     * @return Cache
     */
    public function cache(): Cache;

    /**
     * Traitement de la configuration. Récupération de l'instance|d'un attribut de configuration|Définition d'une liste
     * d'attributs
     *
     * @param string|array|null $key Indice de l'attribut à récupérer ou Liste d'attributs à définir|null pour
     *                               récuperer l'instance
     * @param mixed $default Valeur de retour par défaut si $key est un indice d'attribut à récupérer.
     *
     * @return mixed
     */
    public function config($key = null, $default = null);

    /**
     * Instance du conteneur d'injection de dépendances.
     *
     * @return Container|null
     */
    public function getContainer(): ?Container;

    /**
     * Définition du gestionnaire de cache.
     *
     * @param Cache $cache Instance du gestionnaire de cache.
     *
     * @return static
     */
    public function setCache(Cache $cache): HttpCache;

    /**
     * Définition du géstionnaire de configuration.
     *
     * @param Config $config Instance du gestionnaire de configuration.
     *
     * @return static
     */
    public function setConfig(Config $config): HttpCache;
}