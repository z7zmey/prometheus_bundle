<?php

declare(strict_types = 1);

namespace Prometheus\Metric\Type\Counter;

use Prometheus\Metric\PrometheusMetricInterface;

interface PrometheusMetricCounterInterface extends PrometheusMetricInterface
{
    public function incBy(float $count, array $labels = []);

    public function inc(array $labels = []);
}
