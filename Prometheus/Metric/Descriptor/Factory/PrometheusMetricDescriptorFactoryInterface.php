<?php

declare(strict_types = 1);

namespace Prometheus\Metric\Descriptor\Factory;

use Prometheus\Metric\Descriptor\PrometheusMetricDescriptorInterface;

interface PrometheusMetricDescriptorFactoryInterface
{
    public function make(string $name, array $data): PrometheusMetricDescriptorInterface;
}
