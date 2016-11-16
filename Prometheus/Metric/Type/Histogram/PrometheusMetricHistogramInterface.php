<?php

declare(strict_types = 1);

namespace Prometheus\Metric\Type\Histogram;

use Prometheus\Metric\PrometheusMetricInterface;

interface PrometheusMetricHistogramInterface extends PrometheusMetricInterface
{
    public function observe(float $value, array $labels = []);
}
