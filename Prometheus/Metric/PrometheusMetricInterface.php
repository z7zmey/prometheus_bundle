<?php

declare(strict_types = 1);

namespace Prometheus\Metric;

use Prometheus\Metric\Descriptor\PrometheusMetricDescriptorInterface;

interface PrometheusMetricInterface
{
    public function getDescriptor(): PrometheusMetricDescriptorInterface;

    public function render(): string;
}
