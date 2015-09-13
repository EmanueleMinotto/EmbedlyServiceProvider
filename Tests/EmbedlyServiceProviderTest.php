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
     * Test service registration.
     * 
     * @covers ::boot
     * @covers ::register
     *
     * @return void
     */
    public function testRegisterServiceProvider()
    {
        $app = new Application();
        $app->register(new EmbedlyServiceProvider());
        $app->boot();

        $this->assertInstanceOf('EmanueleMinotto\\Embedly\\Client', $app['embedly']);
    }

    /**
     * Test Twig extension integration in Silex (enabled, default).
     * 
     * @coversNothing
     *
     * @return void
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
     * Test Twig extension integration in Silex (disabled).
     * 
     * @coversNothing
     *
     * @return void
     */
    public function testMissingTwigExtension()
    {
        $app = new Application();
        $app->register(new TwigServiceProvider());
        $app->register(new EmbedlyServiceProvider(), [
            'embedly.twig' => false,
        ]);
        $app->boot();

        $this->assertFalse($app['twig']->hasExtension('emanueleminotto_embedly_twigextension'));
    }

    /**
     * Simulates a request and controls the output (generic).
     *
     * @coversNothing
     *
     * @return void
     */
    public function testRequestGeneric()
    {
        $embed = $this->createTestRequestResponse();

        $this->assertInternalType('array', $embed);
        $this->assertArrayHasKey('type', $embed);
        $this->assertContains($embed['type'], ['photo', 'video', 'rich', 'link', 'error']);
    }

    /**
     * Simulates a request and controls the output (type subsection).
     *
     * @coversNothing
     *
     * @return void
     */
    public function testRequestType()
    {
        $embed = $this->createTestRequestResponse();

        switch ($embed['type']) {
            case 'error':
                $this->assertNotEmpty(array_intersect(array_keys($embed), ['error_code', 'error_message', 'url']));

                break;
            case 'photo':
                $this->assertNotEmpty(array_intersect(array_keys($embed), ['height', 'url', 'width']));

                break;
            case 'rich':
            case 'video':
                $this->assertArrayHasKey('html', $embed);

                break;
        }
    }

    /**
     * Helper method used to create a request.
     *
     * @return array
     */
    private function createTestRequestResponse()
    {
        $app = new Application();
        $app->register(new EmbedlyServiceProvider(), [
            'embedly.api_key' => $_ENV['api_key'],
        ]);

        $app->get('/', function () use ($app) {
            $data = $app['embedly']->oembed([
                'url' => $_ENV['url'],
            ]);

            return $app->json($data);
        });

        $request = Request::create('/');
        $response = $app->handle($request);

        return json_decode($response->getContent(), true);
    }
}
