<?php declare(strict_types=1);

namespace tiFy\Plugins\HttpCache;

use tiFy\Contracts\Container\Container;
use tiFy\Plugins\HttpCache\Contracts\{
    Cache,
    CacheStatic as CacheStaticContract,
    Config as ConfigContract,
    HttpCache as HttpCacheContract};
use tiFy\Plugins\HttpCache\Cache\CacheStatic;

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
class HttpCache implements HttpCacheContract
{
    /**
     * Instance du conteneur d'injection de dépendances.
     * @var Container|null
     */
    protected $container;

    /**
     * Instance du gestionnaire de fichiers de cache.
     * @var Cache
     */
    protected $cache;

    /**
     * Instance du gestionnaire de configuration.
     * @var Config
     */
    protected $config;

    /**
     * CONSTRUCTEUR.
     *
     * @param ConfigContract|array $config Instance du gestionnaire de configuration ou liste
     * @param Container|null $container Instance du conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct($config = [], ?Container $container = null)
    {
        $this->container = $container;

        if (is_array($config)) {
            $config = $this->container
                ? $this->getContainer()->get(ConfigContract::class)->set($config)
                : (new Config())->set($config);
        }
        $this->setConfig($config);
    }

    /**
     * @inheritDoc
     */
    public function cache(): Cache
    {
        if (is_null($this->cache)) {
            if (!$cache = $this->config('cache', null)) {
                $cache = $this->getContainer()
                    ? $this->getContainer()->get(CacheStaticContract::class)
                    : new CacheStatic();
            }
            $this->setCache($cache);
        }

        return $this->cache;
    }

    /**
     * @inheritDoc
     */
    public function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->config;
        } elseif (is_array($key)) {
            return $this->config->set($key);
        } else {
            return $this->config->get($key, $default);
        }
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): ?Container
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function setCache(Cache $cache): HttpCacheContract
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setConfig(ConfigContract $config): HttpCacheContract
    {
        $this->config = $config->parse();

        return $this;
    }
}
