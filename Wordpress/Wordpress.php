<?php declare(strict_types=1);

namespace tiFy\Plugins\HttpCache\Wordpress;

class Wordpress
{
    public function __construct()
    {
        add_action('parse_query', function () {
            // Récupération du chemin vers le répertoire temporaire de PHP
            sys_get_temp_dir();

            add_action('template_redirect', function () {
                $path = WP_CONTENT_DIR . '/uploads/cache/test';
                $path .= '/' .  md5($_SERVER['REQUEST_URI']);
                if (file_exists($path)) {
                    echo gzuncompress(file_get_contents($path));
                    exit;
                }
            }, -1);

            ob_start(function ($content) {
                $path = WP_CONTENT_DIR . '/uploads/cache/test';
                $path .= '/' .  md5($_SERVER['REQUEST_URI']);

                if(false !== ($f = @fopen($path, 'w'))) {
                    fwrite($f, gzcompress($content));
                    fclose($f);
                }

                return $content;
            });
        });
    }
}