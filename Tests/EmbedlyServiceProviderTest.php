<?php

namespace EmanueleMinotto\EmbedlyServiceProvider\Tests;

use EmanueleMinotto\EmbedlyServiceProvider\EmbedlyServiceProvider;
use PHPUnit_Framework_TestCase;
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Emanuele Minotto <minottoemanuele@gmail.com>
 *
 * @coversDefaultClass \EmanueleMinotto\EmbedlyServiceProvider\EmbedlyServiceProvider
 */
class EmbedlyServiceProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::boot
     * @covers ::register
     */
    public function testRegisterServiceProvider()
    {
        $app = new Application();
        $app->register(new EmbedlyServiceProvider());
        $app->boot();

        $this->assertInstanceOf('EmanueleMinotto\\Embedly\\Client', $app['embedly']);
    }

    /**
     * @coversNothing
     */
    public function testTwigExtension()
    {
        $app = new Application();
        $app->register(new TwigServiceProvider());
        $app->register(new EmbedlyServiceProvider());
        $app->boot();

        $this->assertTrue($app['twig']->hasExtension('emanueleminotto_embedly_twigextension'));
    }

    /**
     * @coversNothing
     */
    public function testMissingTwigExtension()
    {
        $app = new Application();
        $app->register(new TwigServiceProvider());
        $app->register(new EmbedlyServiceProvider(), array(
            'embedly.twig' => false,
        ));
        $app->boot();

        $this->assertFalse($app['twig']->hasExtension('emanueleminotto_embedly_twigextension'));
    }

    /**
     * Simulates a request and controls the output.
     *
     * @coversNothing
     */
    public function testRequest()
    {
        $app = new Application();
        $app->register(new EmbedlyServiceProvider(), array(
            'embedly.api_key' => $_ENV['api_key'],
        ));

        $app->get('/', function () use ($app) {
            $data = $app['embedly']->oembed([
                'url' => $_ENV['url'],
            ]);

            return $app->json($data);
        });

        $request = Request::create('/');
        $response = $app->handle($request);

        $embed = json_decode($response->getContent(), true);

        $this->assertInternalType('array', $embed);

        $this->assertArrayHasKey('type', $embed);
        $this->assertContains($embed['type'], ['photo', 'video', 'rich', 'link', 'error']);

        switch ($embed['type']) {
            case 'error':
                $this->assertArrayHasKey('error_code', $embed);
                $this->assertArrayHasKey('error_message', $embed);
                $this->assertArrayHasKey('url', $embed);

                break;
            case 'photo':
                $this->assertArrayHasKey('height', $embed);
                $this->assertArrayHasKey('url', $embed);
                $this->assertArrayHasKey('width', $embed);

                break;
            case 'rich':
            case 'video':
                $this->assertArrayHasKey('html', $embed);

                break;
        }
    }
}
