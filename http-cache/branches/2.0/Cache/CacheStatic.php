<?php declare(strict_types=1);

namespace tiFy\Plugins\HttpCache\Cache;

use League\Flysystem\FileNotFoundException;
use tiFy\Contracts\Container\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use tiFy\Plugins\HttpCache\Contracts\CacheStatic as CacheStaticContract;
use tiFy\Filesystem\{Filesystem, StorageManager};

class CacheStatic extends Filesystem implements CacheStaticContract
{
    /**
     * CONSTRUCTEUR
     *
     * @param string|null Chemin absolu vers la racine du répertoire de stockage.
     * @param Container $container Instance du conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(?string $root = null, ?Container $container = null)
    {
        $adapter = (new StorageManager($container))->localAdapter($root ?: WP_CONTENT_DIR . '/uploads/cache/HttpCache');

        parent::__construct($adapter, []);
    }

    /**
     * {@inheritDoc}
     *
     * @throws FileNotFoundException
     */
    public function contents($path): string
    {
        return $this->read($path);
    }

    /**
     * @inheritDoc
     */
    public function exists($path): bool
    {
        return $this->has($path);
    }

    /**
     * @inheritDoc
     */
    public function flush(): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @throws FileNotFoundException
     */
    public function forget($path): bool
    {
        return $this->delete($path);
    }

    /**
     * @inheritDoc
     */
    public function hash(Request $request): string
    {
        $segments = $this->segments($request);

        $name = $request->getUri()->getHost() . $request->getUri()->getPath();
        $name .= ($query = $request->getUri()->getQuery()) ? "?{$query}" : '';
        $segments[] = md5($name);

        $hash = implode('/', $segments);

        return $hash;
    }

    /**
     * @inheritDoc
     */
    public function segments(Request $request): array
    {
        $segments = preg_split('#\/#', $request->getUri()->getPath(), 0, PREG_SPLIT_NO_EMPTY) ?: [];
        array_unshift($segments, $request->getUri()->getHost());

        return $segments;
    }

    /**
     * @inheritDoc
     */
    public function store($key, $value, $expire): bool
    {
        return $this->put($key, $value);
    }
}