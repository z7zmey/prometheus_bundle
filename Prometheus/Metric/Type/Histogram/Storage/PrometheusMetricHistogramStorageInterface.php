<?php

declare(strict_types = 1);

namespace Prometheus\Metric\Type\Histogram\Storage;

use Prometheus\Metric\Descriptor\PrometheusMetricDescriptorInterface as Descriptor;

interface PrometheusMetricHistogramStorageInterface
{
    public function incrementHistogramBucket(Descriptor $metricDescriptor, $value, array $labels);

    public function addHistogramBucket(Descriptor $metricDescriptor, array $labels);

    public function incrementHistogramSum(Descriptor $metricDescriptor, $value, array $labels = []);

    public function incrementHistogramCount(Descriptor $metricDescriptor, $value, array $labels = []);

    public function incrementHistogramInf(Descriptor $metricDescriptor, $value, array $labels = []);

    public function getHistogramBuckets(Descriptor $metricDescriptor): \Generator ;

    public function getHistogramSum(Descriptor $metricDescriptor): \Generator;

    public function getHistogramCount(Descriptor $metricDescriptor): \Generator;

    public function getHistogramInf(Descriptor $metricDescriptor): \Generator;
}
