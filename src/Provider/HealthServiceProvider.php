<?php

namespace Actuator\Slim\Provider;

use Actuator\Health\Health;
use Actuator\Health\HealthAggregatorInterface;
use Actuator\Health\Indicator\CompositeHealthIndicator;
use Actuator\Health\Indicator\HealthIndicatorInterface;
use Psr\Http\Message\ResponseInterface;

class HealthServiceProvider
{
    /**
     * @var HealthAggregatorInterface
     */
    protected $aggregator;

    /**
     * @var HealthIndicatorInterface[]
     */
    protected $indicators;

    /**
     * HealthServiceProvider constructor.
     * @param HealthAggregatorInterface $aggregator
     * @param \Actuator\Health\Indicator\HealthIndicatorInterface[] $indicators
     */
    public function __construct(HealthAggregatorInterface $aggregator, array $indicators)
    {
        $this->aggregator = $aggregator;
        $this->indicators = $indicators;
    }

    /**
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function getHealth(ResponseInterface $response)
    {
        $healthResult = $this->getHealthResult();
        $healthBody = $this->formatHealthResult($healthResult);

        $response = $response->withHeader('Content-Type', 'application/json');
        $body = $response->getBody();
        $body->write(json_encode($healthBody));
        return $response;
    }

    /**
     * @return Health
     */
    private function getHealthResult()
    {
        $healthIndicator = new CompositeHealthIndicator($this->aggregator);
        foreach ($this->indicators as $key => $entry) {
            $healthIndicator->addHealthIndicator($key, $entry);
        }

        return $healthIndicator->health();
    }

    /**
     * @param Health $healthResult
     * @return array
     */
    private function formatHealthResult(Health $healthResult)
    {
        $healthDetails = array();
        foreach ($healthResult->getDetails() as $key => $healthDetail) {
            $healthDetails[$key] = array_merge(
                array('status' => $healthDetail->getStatus()->getCode()),
                $healthDetail->getDetails()
            );
        }
        $healthDetails = array_merge(
            array('status' => $healthResult->getStatus()->getCode()),
            $healthDetails
        );

        return $healthDetails;
    }
}
