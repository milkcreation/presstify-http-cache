<?php declare(strict_types=1);

namespace tiFy\Plugins\HttpCache;

use tiFy\Container\ServiceProvider;
use tiFy\Plugins\HttpCache\Contracts\ResponseCache as ResponseCacheContract;
use tiFy\Plugins\HttpCache\Middleware\CacheResponse;
use tiFy\Support\Proxy\Router;

class HttpCacheServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * {@internal Permet le chargement différé des services qualifié.}
     * @var string[]
     */
    protected $provides = [
        'http-cache',
        ResponseCacheContract::class,
        'router.middleware.cache-response'
    ];

    /**
     * @inheritDoc
     */
    public function boot()
    {
        add_action('after_setup_theme', function () {
            $this->getContainer()->get('http-cache');

            Router::middleware($this->getContainer()->get('router.middleware.cache-response'));
        });
    }

    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->getContainer()->share('http-cache', function () {
            return new HttpCache($this->getContainer());
        });

        $this->getContainer()->add(ResponseCacheContract::class, function () {
            return new ResponseCache($this->getContainer()->get('http-cache'));
        });

        $this->getContainer()->add('router.middleware.cache-response', function () {
            return new CacheResponse($this->getContainer()->get(ResponseCacheContract::class));
        });
    }
}