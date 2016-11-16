<?php

declare(strict_types = 1);

namespace Prometheus\Metric\Descriptor\Repository;

use ArrayIterator;
use Prometheus\Metric\Descriptor\Factory\PrometheusMetricDescriptorFactoryInterface;
use Prometheus\Metric\Descriptor\PrometheusMetricDescriptorInterface;

class PrometheusMetricDescriptorRepository implements PrometheusMetricDescriptorRepositoryInterface
{
    /**
     * @var PrometheusMetricDescriptorFactoryInterface
     */
    protected $factory;

    /**
     * @var PrometheusMetricDescriptorInterface[]
     */
    protected $descriptors;

    public function __construct(PrometheusMetricDescriptorFactoryInterface $factory, array $metrics)
    {
        $this->factory = $factory;

        foreach ($metrics as $metricName => $metricData) {
            $this->descriptors[$metricName] = $this->factory->make($metricName, $metricData);
        }
    }

    public function getDescriptor(string $name): PrometheusMetricDescriptorInterface
    {
        return $this->descriptors[$name];
    }

    public function getIterator()
    {
        return new ArrayIterator($this->descriptors);
    }
}
