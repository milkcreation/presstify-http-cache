<?php declare(strict_types=1);

namespace tiFy\Plugins\HttpCache;

use tiFy\Contracts\Container\Container;
use tiFy\Contracts\Filesystem\Filesystem;
use tiFy\Filesystem\StorageManager;

/**
 * Class HttpCache
 *
 * @desc Extension PresstiFy de mise en cache de la reponse HTTP.
 * @author Jordy Manner <jordy@milkcreation.fr>
 * @package tiFy\Plugins\HttpCache
 * @version 2.0.0
 *
 * @see https://symfony.com/doc/current/http_cache.html
 * @see https://github.com/spatie/laravel-responsecache
 * @see https://murze.be/caching-the-entire-response-of-a-laravel-app
 *
 * USAGE :
 * Activation
 * ---------------------------------------------------------------------------------------------------------------------
 * Dans config/app.php ajouter \tiFy\Plugins\HttpCache\HttpCacheServiceProvider à la liste des fournisseurs de services.
 * ex.
 * <?php
 * ...
 * use tiFy\Plugins\HttpCache\HttpCacheServiceProvider;
 * ...
 *
 * return [
 *      ...
 *      'providers' => [
 *          ...
 *          HttpCacheServiceProvider::class
 *          ...
 *      ]
 * ];
 *
 * Configuration
 * ---------------------------------------------------------------------------------------------------------------------
 * Dans le dossier de config, créer le fichier http-cache.php
 * @see /vendor/presstify-plugins/http-cache/Resources/config/http-cache.php
 */
class HttpCache extends StorageManager
{
    /**
     * Instance du conteneur d'injection de dépendances.
     * @var Container|null
     */
    protected $container;

    /**
     * Instance du gestionnaire de fichiers de cache.
     * @var Filesystem
     */
    protected $cache;

    /**
     * CONSTRUCTEUR.
     *
     * @param Container|null $container Instance du conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(?Container $container = null)
    {
        $this->container = $container;

        parent::__construct($container);
    }

    /**
     * Récupération de l'instance du gestionnaire de fichiers de cache.
     *
     * @return Filesystem
     */
    public function cache()
    {
        if (is_null($this->cache)) {
            $this->cache = $this->createLocal(WP_CONTENT_DIR . '/uploads/cache/HttpCache');
        }

        return $this->cache;
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): ?Container
    {
        return $this->container;
    }
}