<?php

declare(strict_types = 1);

namespace Prometheus\Metric\Type\Counter\Storage;

use Prometheus\Metric\Descriptor\PrometheusMetricDescriptorInterface as Descriptor;

interface PrometheusMetricCounterStorageInterface
{
    public function incrementCounter(Descriptor $metricDescriptor, $value, array $labels);

    public function getCounter(Descriptor $metricDescriptor): \Generator;
}
