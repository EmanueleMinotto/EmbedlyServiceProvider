<?php

namespace EmanueleMinotto\EmbedlyServiceProvider;

use EmanueleMinotto\Embedly\Client;
use EmanueleMinotto\Embedly\Twig\EmbedlyExtension;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * An embed.ly service provider for Silex 1.
 *
 * @author Emanuele Minotto <minottoemanuele@gmail.com>
 *
 * @link http://silex.sensiolabs.org/doc/providers.html#creating-a-provider
 */
class EmbedlyServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        $app['embedly'] = $app->share(function ($app) {
            return new Client();
        });

        $app['embedly.api_key'] = null;
        $app['embedly.twig'] = true;
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
        if (null === $app['embedly.api_key']) {
            $app['embedly']->setApiKey($app['embedly.api_key']);
        }

        // Twig extension
        if (isset($app['twig']) && (boolean) $app['embedly.twig']) {
            $extension = new EmbedlyExtension($app['embedly']);
            $app['twig']->addExtension($extension);
        }
    }
}
