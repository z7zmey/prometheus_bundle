<?php

declare(strict_types = 1);

namespace Prometheus\Metric\Descriptor\Repository;

use Prometheus\Metric\Descriptor\PrometheusMetricDescriptorInterface;

interface PrometheusMetricDescriptorRepositoryInterface extends \IteratorAggregate
{
    public function getDescriptor(string $name): PrometheusMetricDescriptorInterface;
}
