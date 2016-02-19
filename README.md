# php-actuator-slim-provider

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Slim Provider for php-actuator

## Install

Via Composer

``` bash
$ composer require postalservice14/php-actuator-slim-provider
```

## Parameters

* **health.indicators**: An array of indicators to be used. Key as indicator name, value as indicator object.
* **health.endpoint**: Endpoint for health checks.  Defaults to "/health".

## Registering

```php
$container = $app->getContainer();
$container['health.aggregator'] = new OrderedHealthAggregator();
$container['health.indicators'] = array(
    'disk' => new DiskSpaceHealthIndicator()
);
$container['health'] = function ($container) {
    return new HealthServiceProvider(
        $container['health.aggregator'],
        $container['health.indicators']
    );
};
```

## Usage

Setup the route you would like your health check on.  e.g.:

```php
$app->get('/health', function ($req, $res) {
    return $this->health->getHealth($res);
});
```

Then visit your endpoint.  In this case: `/health`

## Getting Started

The following is a minimal example to get you started quickly.  It uses the 
[DiskSpaceHealthIndicator](src/Health/Indicator/DiskSpaceHealthIndicator.php).

* Create a composer.json with at minimum, the following dependencies

```json
{
    "require": {
        "postalservice14/php-actuator-slim-provider": "^1.0"
    }
}
```

* Run composer install
* Create /public/index.php

```php
<?php

require_once __DIR__.'/../vendor/autoload.php';

use Slim\App;
use Actuator\Health\OrderedHealthAggregator;
use Actuator\Health\Indicator\DiskSpaceHealthIndicator;
use Actuator\Slim\Provider\HealthServiceProvider;

$indicators = array(
    'disk' => new DiskSpaceHealthIndicator()
);

$app = new App();

$container = $app->getContainer();
$container['health.aggregator'] = new OrderedHealthAggregator();
$container['health.indicators'] = $indicators;
$container['health'] = function ($container) {
    return new HealthServiceProvider(
        $container['health.aggregator'],
        $container['health.indicators']
    );
};

$app->get('/health', function ($req, $res) {
    return $this->health->getHealth($res);
});
$app->run();
```

* Run the service `php -S localhost:8000 -t public public/index.php`
* Go to http://localhost:8000/health to see your health indicator.

## Example

Available at [/example](example/index.php)

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Credits

- [John Kelly][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/postalservice14/php-actuator-slim-provider.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/postalservice14/php-actuator-slim-provider/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/postalservice14/php-actuator-slim-provider.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/postalservice14/php-actuator-slim-provider.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/postalservice14/php-actuator-slim-provider.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/postalservice14/php-actuator-slim-provider
[link-travis]: https://travis-ci.org/postalservice14/php-actuator-slim-provider
[link-scrutinizer]: https://scrutinizer-ci.com/g/postalservice14/php-actuator-slim-provider/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/postalservice14/php-actuator-slim-provider
[link-downloads]: https://packagist.org/packages/postalservice14/php-actuator-slim-provider
[link-author]: https://github.com/postalservice14
[link-contributors]: ../../contributors
