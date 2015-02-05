Embedly Service Provider [![Build Status](https://travis-ci.org/EmanueleMinotto/EmbedlyServiceProvider.svg)](https://travis-ci.org/EmanueleMinotto/EmbedlyServiceProvider)
====================

An [embed.ly](http://embed.ly) service provider for [Silex](http://silex.sensiolabs.org/).

## Install
Install Silex using [Composer](http://getcomposer.org/).

Install the EmbedlyServiceProvider adding `emanueleminotto/embedly-service-provider` to your composer.json or from CLI:

```
$ composer require emanueleminotto/embedly-service-provider
```

## Usage

Initialize it using `register`

```php
use EmanueleMinotto\EmbedlyServiceProvider\EmbedlyServiceProvider;

$app->register(new EmbedlyServiceProvider(), array(
    'embedly.api_key' => 'xxx', // default null, optional
    'embedly.twig' => false, // default true, optional
));
```

From PHP
```php
use Silex\Application;

$app->get('/get', function (Application $app) {
    $url = $app['request']->get('url');

    $data = $app['embedly']->oembed([
        'url' => $url,
    ]);

    return $app->json($data);
});
```

From [Twig](http://twig.sensiolabs.org/)

Setting the option `embedly.twig => true`, if there's the [Twig service provider](http://silex.sensiolabs.org/doc/providers/twig.html), you'll be able to use the [Twig extension](https://github.com/EmanueleMinotto/Embedly#twig-extension) provided by the [Embedly library](https://github.com/EmanueleMinotto/Embedly).
