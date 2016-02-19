<?php

namespace Actuator\Slim\Test\Provider;

use Actuator\Health\Indicator\DiskSpaceHealthIndicator;
use Actuator\Health\Indicator\DiskSpaceHealthIndicatorProperties;
use Actuator\Health\OrderedHealthAggregator;
use Actuator\Health\Status;
use Actuator\Slim\Provider\HealthServiceProvider;
use Slim\App;
use Slim\Http\Response;

class HealthServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testConfigServiceProviders()
    {
        $aggregator = new OrderedHealthAggregator();
        $indicators = array(
            'diskspace' => new DiskSpaceHealthIndicator()
        );
        $serviceProvider = new HealthServiceProvider($aggregator, $indicators);

        $response = new Response();
        $response = $serviceProvider->getHealth($response);

        $this->assertTrue($response->hasHeader('Content-Type'));
        $this->assertEquals('application/json', $response->getHeader('Content-Type')[0]);

        $jsonResult = json_decode($response->getBody(), true);
        $this->assertEquals(Status::UP, $jsonResult['status']);
        $this->assertCount(4, $jsonResult['diskspace']);
    }
}
