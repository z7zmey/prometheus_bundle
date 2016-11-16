<?php

declare(strict_types = 1);

namespace Prometheus\Metric\Factory;

use Prometheus\Metric\Descriptor\PrometheusMetricDescriptorInterface;
use Prometheus\Metric\Type\Counter\PrometheusMetricCounter;
use Prometheus\Metric\Type\Gauge\PrometheusMetricGauge;
use Prometheus\Metric\Type\Histogram\PrometheusMetricHistogram;

interface PrometheusMetricFactoryInterface
{
    public function makeCounter(PrometheusMetricDescriptorInterface $descriptor): PrometheusMetricCounter;

    public function makeGauge(PrometheusMetricDescriptorInterface $descriptor): PrometheusMetricGauge;

    public function makeHistogram(PrometheusMetricDescriptorInterface $descriptor, array $buckets): PrometheusMetricHistogram;
}
