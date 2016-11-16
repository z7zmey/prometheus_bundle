<?php

declare(strict_types = 1);

namespace Prometheus\Metric\Type\Gauge;

use Prometheus\Metric\PrometheusMetricInterface;

interface PrometheusMetricGaugeInterface extends PrometheusMetricInterface
{
    public function incBy(float $count, array $labels = []);

    public function inc(array $labels = []);

    public function set(float $count, array $labels = []);
}
