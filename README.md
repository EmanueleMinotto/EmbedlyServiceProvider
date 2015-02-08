Embedly Service Provider
========================

[![Build Status](https://img.shields.io/travis/EmanueleMinotto/EmbedlyServiceProvider.svg?style=flat)](https://travis-ci.org/EmanueleMinotto/EmbedlyServiceProvider)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/95a26db2-bd04-4c3c-b3e5-37cab79fa6b7.svg?style=flat)](https://insight.sensiolabs.com/projects/95a26db2-bd04-4c3c-b3e5-37cab79fa6b7)
[![Coverage Status](https://img.shields.io/coveralls/EmanueleMinotto/EmbedlyServiceProvider.svg?style=flat)](https://coveralls.io/r/EmanueleMinotto/EmbedlyServiceProvider)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/EmanueleMinotto/EmbedlyServiceProvider.svg?style=flat)](https://scrutinizer-ci.com/g/EmanueleMinotto/EmbedlyServiceProvider/)
[![Total Downloads](https://img.shields.io/packagist/dt/emanueleminotto/embedly-service-provider.svg?style=flat)](https://packagist.org/packages/emanueleminotto/embedly-service-provider)

An [embed.ly](http://embed.ly) service provider for [Silex](http://silex.sensiolabs.org/).

API: [emanueleminotto.github.io/EmbedlyServiceProvider](http://emanueleminotto.github.io/EmbedlyServiceProvider/)

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
