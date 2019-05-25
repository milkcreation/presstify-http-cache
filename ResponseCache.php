<?php declare(strict_types=1);

namespace tiFy\Plugins\ResponseCache;

use Psr\Container\ContainerInterface;

/**
 * Class ResponseCache
 *
 * @desc Extension PresstiFy de mise en cache de la reponse HTTP.
 * @author Jordy Manner <jordy@milkcreation.fr>
 * @package tiFy\Plugins\ResponseCache
 * @version 2.0.0
 *
 * USAGE :
 * Activation
 * ---------------------------------------------------------------------------------------------------------------------
 * Dans config/app.php ajouter \tiFy\Plugins\ResponseCache\ResponseCacheServiceProvider à la liste des fournisseurs de
 * services.
 * ex.
 * <?php
 * ...
 * use tiFy\Plugins\ResponseCache\ResponseCacheServiceProvider;
 * ...
 *
 * return [
 *      ...
 *      'providers' => [
 *          ...
 *          ResponseCacheServiceProvider::class
 *          ...
 *      ]
 * ];
 *
 * Configuration
 * ---------------------------------------------------------------------------------------------------------------------
 * Dans le dossier de config, créer le fichier response-cache.php
 * @see /vendor/presstify-plugins/response-cache/Resources/config/response-cache.php
 */
class ResponseCache
{
    /**
     * Instance du conteneur d'injection de dépendances.
     * @var ContainerInterface|null
     */
    protected $container;

    /**
     * CONSTRUCTEUR.
     *
     * @param ContainerInterface|null Instance du conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Récupération du conteneur d'injection de dépendances.
     *
     * @return ContainerInterface|null
     */
    public function getContainer(): ?ContainerInterface
    {
        return $this->container;
    }
}