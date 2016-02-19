<?php

require_once __DIR__.'/../vendor/autoload.php';

use Slim\App;
use Actuator\Health\OrderedHealthAggregator;
use Actuator\Health\Indicator\DiskSpaceHealthIndicator;
use Actuator\Health\Indicator\MemcachedHealthIndicator;
use Actuator\Slim\Provider\HealthServiceProvider;

$memcached = new Memcached();
$memcached->addServer('127.0.0.1', 11211);

$indicators = array(
    'memcache' => new MemcachedHealthIndicator($memcached),
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
