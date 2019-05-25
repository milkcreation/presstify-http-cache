<?php declare(strict_types=1);

namespace tiFy\Plugins\HttpCache;

use Psr\Container\ContainerInterface;

/**
 * Class HttpCache
 *
 * @desc Extension PresstiFy de mise en cache de la reponse HTTP.
 * @author Jordy Manner <jordy@milkcreation.fr>
 * @package tiFy\Plugins\HttpCache
 * @version 2.0.0
 *
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
class HttpCache
{
    /**
     * Instance du conteneur d'injection de dépendances.
     * @var ContainerInterface|null
     */
    protected $container;

    /**
     * CONSTRUCTEUR.
     *
     * @param ContainerInterface|null $container Instance du conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(?ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): ?ContainerInterface
    {
        return $this->container;
    }
}