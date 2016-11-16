<?php

declare(strict_types = 1);

namespace Prometheus\Metric\Descriptor\Factory;

use Prometheus\Metric\Descriptor\PrometheusMetricDescriptor;
use Prometheus\Metric\Descriptor\PrometheusMetricDescriptorInterface;

class PrometheusMetricDescriptorFactory implements PrometheusMetricDescriptorFactoryInterface
{
    public function make(string $name, array $data = []): PrometheusMetricDescriptorInterface
    {
        return new PrometheusMetricDescriptor($data['type'], $name, $data['help'], $data['labels']);
    }
}
