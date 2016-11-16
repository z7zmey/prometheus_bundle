<?php

declare(strict_types = 1);

namespace Prometheus\Metric\Repository;

use Prometheus\Metric\Type\Counter\PrometheusMetricCounterInterface;
use Prometheus\Metric\Type\Gauge\PrometheusMetricGaugeInterface;
use Prometheus\Metric\Type\Histogram\PrometheusMetricHistogramInterface;

interface PrometheusMetricRepositoryInterface extends \IteratorAggregate
{

    /**
     * @param string $name
     *
     * @return PrometheusMetricCounterInterface
     */
    public function getCounter(string $name): PrometheusMetricCounterInterface;

    /**
     * @param string $name
     *
     * @return PrometheusMetricGaugeInterface
     */
    public function getGauge(string $name): PrometheusMetricGaugeInterface;

    /**
     * @param string $name
     *
     * @return PrometheusMetricHistogramInterface
     */
    public function getHistogram(string $name): PrometheusMetricHistogramInterface;
}
