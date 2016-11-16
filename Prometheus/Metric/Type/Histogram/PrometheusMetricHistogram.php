<?php

declare(strict_types = 1);

namespace Prometheus\Metric\Type\Histogram;

use Prometheus\Metric\PrometheusAbstractMetric;
use Prometheus\Metric\Descriptor\PrometheusMetricDescriptorInterface;
use Prometheus\Metric\Render\PrometheusMetricRender;
use Prometheus\Metric\Storage\PrometheusMetricStorageInterface;
use Prometheus\Metric\Type\Histogram\Storage\PrometheusMetricHistogramStorageInterface;

class PrometheusMetricHistogram extends PrometheusAbstractMetric implements PrometheusMetricHistogramInterface
{
    private $buckets;

    /**
     * PrometheusMetricHistogram constructor.
     *
     * @param PrometheusMetricRender                                                     $render
     * @param PrometheusMetricDescriptorInterface                                        $descriptor
     * @param PrometheusMetricStorageInterface|PrometheusMetricHistogramStorageInterface $storage
     * @param array                                                                      $buckets
     */
    public function __construct(
        PrometheusMetricRender $render,
        PrometheusMetricDescriptorInterface $descriptor,
        PrometheusMetricStorageInterface $storage,
        array $buckets = []
    ) {
        parent::__construct($render, $descriptor, $storage);
        $this->buckets = $buckets;
    }

    /**
     * @param float $value
     * @param array $labels
     *
     * @throws \InvalidArgumentException
     */
    public function observe(float $value, array $labels = array())
    {
        if (count($this->descriptor->getLabels()) !== count($labels)) {
            throw new \InvalidArgumentException('Wrong labels count');
        }

        foreach ($this->buckets as $bucket) {
            $bucket = (float)$bucket;
            $bucketLabels = array_merge($labels, [$bucket]);

            $this->storage->addHistogramBucket($this->descriptor, $bucketLabels);

            if ($value <= $bucket) {
                $this->storage->incrementHistogramBucket($this->descriptor, 1, $bucketLabels);
            }
        }

        $this->storage->incrementHistogramInf($this->descriptor, 1, $labels);
        $this->storage->incrementHistogramSum($this->descriptor, $value, $labels);
        $this->storage->incrementHistogramCount($this->descriptor, 1, $labels);
    }

    public function render(): string
    {
        return $this->render->renderHistogram($this);
    }
}
