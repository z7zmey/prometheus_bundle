<?php

declare(strict_types = 1);

namespace Prometheus\Metric;

use Prometheus\Metric\Descriptor\PrometheusMetricDescriptorInterface;
use Prometheus\Metric\Render\PrometheusMetricRender;
use Prometheus\Metric\Storage\PrometheusMetricStorageInterface;

abstract class PrometheusAbstractMetric implements PrometheusMetricInterface
{
    /**
     * @var PrometheusMetricDescriptorInterface
     */
    protected $descriptor;

    /**
     * @var PrometheusMetricStorageInterface
     */
    protected $storage;

    /**
     * @var PrometheusMetricRender
     */
    protected $render;

    /**
     * PrometheusAbstractMetric constructor.
     *
     * @param PrometheusMetricRender                $render
     * @param PrometheusMetricDescriptorInterface   $descriptor
     * @param PrometheusMetricStorageInterface $storage
     */
    public function __construct(
        PrometheusMetricRender $render,
        PrometheusMetricDescriptorInterface $descriptor,
        PrometheusMetricStorageInterface $storage
    ) {
        $this->render = $render;
        $this->descriptor = $descriptor;
        $this->storage = $storage;
    }

    /**
     * @return PrometheusMetricDescriptorInterface
     */
    public function getDescriptor(): PrometheusMetricDescriptorInterface
    {
        return $this->descriptor;
    }
}
