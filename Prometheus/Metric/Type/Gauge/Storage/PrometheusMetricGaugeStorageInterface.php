<?php

declare(strict_types = 1);

namespace Prometheus\Metric\Type\Gauge\Storage;

use Prometheus\Metric\Descriptor\PrometheusMetricDescriptorInterface;

interface PrometheusMetricGaugeStorageInterface
{
    public function incrementGauge(PrometheusMetricDescriptorInterface $metricDescriptor, $value, array $labels);

    public function setGauge(PrometheusMetricDescriptorInterface $metricDescriptor, $value, array $labels);

    public function getGauge(PrometheusMetricDescriptorInterface $metricDescriptor): \Generator;
}
