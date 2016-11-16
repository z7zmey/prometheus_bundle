<?php

declare(strict_types = 1);

namespace Prometheus\Metric\Render;

use Prometheus\Metric\Type\Counter\PrometheusMetricCounterInterface;
use Prometheus\Metric\Type\Gauge\PrometheusMetricGaugeInterface;
use Prometheus\Metric\Type\Histogram\PrometheusMetricHistogramInterface;

interface PrometheusMetricRenderInterface
{
    public function renderCounter(PrometheusMetricCounterInterface $metric): string;

    public function renderGauge(PrometheusMetricGaugeInterface $metric): string;

    public function renderHistogram(PrometheusMetricHistogramInterface $metric): string;
}
