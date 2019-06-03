<?php declare(strict_types=1);

namespace tiFy\Plugins\HttpCache;

use tiFy\Container\ServiceProvider;
use tiFy\Plugins\HttpCache\Contracts\{
    Config as ConfigContract,
    CacheStatic as CacheStaticContract,
    ResponseCache as ResponseCacheContract};
use tiFy\Plugins\HttpCache\{Cache\CacheStatic, Middleware\CacheResponse};
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
        CacheStaticContract::class,
        ConfigContract::class,
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
            $config = $this->getContainer()->get(ConfigContract::class)->set(config('http-cache', []));

            return new HttpCache($config, $this->getContainer());
        });

        $this->getContainer()->add(CacheStaticContract::class, function (string $root = '') {
            return new CacheStatic($root, $this->getContainer());
        });

        $this->getContainer()->add(ConfigContract::class, function () {
            return new Config();
        });

        $this->getContainer()->add(ResponseCacheContract::class, function () {
            return new ResponseCache($this->getContainer()->get('http-cache'));
        });

        $this->getContainer()->add('router.middleware.cache-response', function () {
            return new CacheResponse($this->getContainer()->get(ResponseCacheContract::class));
        });
    }
}