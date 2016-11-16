<?php

declare(strict_types = 1);

namespace Prometheus\Metric\Factory;

use Prometheus\Metric\Descriptor\PrometheusMetricDescriptorInterface as Descriptor;
use Prometheus\Metric\Render\PrometheusMetricRender;
use Prometheus\Metric\Storage\PrometheusMetricStorageInterface;
use Prometheus\Metric\Type\Counter\PrometheusMetricCounter;
use Prometheus\Metric\Type\Gauge\PrometheusMetricGauge;
use Prometheus\Metric\Type\Histogram\PrometheusMetricHistogram;

class PrometheusMetricFactory implements PrometheusMetricFactoryInterface
{
    /**
     * @var PrometheusMetricStorageInterface
     */
    protected $storage;

    /**
     * @var PrometheusMetricRender
     */
    protected $render;

    /**
     * PrometheusMetricFactory constructor.
     *
     * @param PrometheusMetricStorageInterface $storage
     * @param PrometheusMetricRender           $render
     */
    public function __construct(PrometheusMetricStorageInterface $storage, PrometheusMetricRender $render)
    {
        $this->storage = $storage;
        $this->render = $render;
    }

    /**
     * @param Descriptor $descriptor
     *
     * @return PrometheusMetricCounter
     */
    public function makeCounter(Descriptor $descriptor): PrometheusMetricCounter
    {
        return new PrometheusMetricCounter($this->render, $descriptor, $this->storage);
    }

    /**
     * @param Descriptor $descriptor
     *
     * @return PrometheusMetricGauge
     */
    public function makeGauge(Descriptor $descriptor): PrometheusMetricGauge
    {
        return new PrometheusMetricGauge($this->render, $descriptor, $this->storage);
    }

    /**
     * @param Descriptor $descriptor
     * @param array      $buckets
     *
     * @return PrometheusMetricHistogram
     */
    public function makeHistogram(Descriptor $descriptor, array $buckets): PrometheusMetricHistogram
    {
        return new PrometheusMetricHistogram($this->render, $descriptor, $this->storage, $buckets);
    }
}
