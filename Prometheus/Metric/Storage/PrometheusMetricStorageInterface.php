<?php

declare(strict_types=1);

namespace Prometheus\Metric\Storage;

use Prometheus\Metric\Type\Counter\Storage\PrometheusMetricCounterStorageInterface;
use Prometheus\Metric\Type\Gauge\Storage\PrometheusMetricGaugeStorageInterface;
use Prometheus\Metric\Type\Histogram\Storage\PrometheusMetricHistogramStorageInterface;

interface PrometheusMetricStorageInterface extends PrometheusMetricCounterStorageInterface,
    PrometheusMetricGaugeStorageInterface, PrometheusMetricHistogramStorageInterface
{

}
