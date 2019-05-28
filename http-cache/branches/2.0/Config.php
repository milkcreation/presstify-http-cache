<?php declare(strict_types=1);

namespace tiFy\Plugins\HttpCache;

use tiFy\Support\ParamsBag;
use tiFy\Plugins\HttpCache\Contracts\Config as ConfigContract;

class Config extends ParamsBag implements ConfigContract
{
    /**
     * {@inheritDoc}
     *
     * @return array {
     * @var boolean $enabled Indicateur d'activation de la mise en cache.
     * @var int $expire Délai d'expiration d'un élément en cache (en secondes). 1 jour par défaut.
     * @var boolean $minify Minification de l'élément en cache.
     * @var boolean $compress Activation de la compression de l'élément en cache.
     * }
     */
    public function defaults()
    {
        return [
            'enabled'  => true,
            'expire'   => 60 * 60 * 24,
            'minify'   => true,
            'compress' => true
        ];
    }
}