<?php declare(strict_types=1);

namespace tiFy\Plugins\ResponseCache;

use tiFy\Container\ServiceProvider;

class ResponseCacheServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * {@internal Permet le chargement différé des services qualifié.}
     * @var string[]
     */
    protected $provides = [
        'response-cache'
    ];

    /**
     * @inheritDoc
     */
    public function boot()
    {
        add_action('after_setup_theme', function () {
            $this->getContainer()->get('response-cache');
        });
    }

    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->getContainer()->share('response-cache', function () {
            return new ResponseCache($this->getContainer());
        });
    }
}