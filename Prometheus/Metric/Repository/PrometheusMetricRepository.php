<?php

declare(strict_types = 1);

namespace Prometheus\Metric\Repository;

use Prometheus\Metric\Descriptor\PrometheusMetricDescriptor;
use Prometheus\Metric\Descriptor\Repository\PrometheusMetricDescriptorRepositoryInterface;
use Prometheus\Metric\Factory\PrometheusMetricFactoryInterface;
use Prometheus\Metric\Type\Counter\PrometheusMetricCounterInterface;
use Prometheus\Metric\Type\Gauge\PrometheusMetricGaugeInterface;
use Prometheus\Metric\Type\Histogram\PrometheusMetricHistogramInterface;

class PrometheusMetricRepository implements PrometheusMetricRepositoryInterface
{
    /**
     * @var PrometheusMetricFactoryInterface
     */
    protected $metricFactory;

    /**
     * @var PrometheusMetricDescriptorRepositoryInterface
     */
    protected $descriptorRepository;

    /**
     * @var PrometheusMetricCounterInterface[]
     */
    protected $counters = [];

    /**
     * @var PrometheusMetricGaugeInterface[]
     */
    protected $gauges = [];

    /**
     * @var PrometheusMetricHistogramInterface[]
     */
    protected $histograms = [];

    /**
     * PrometheusRepositoryMetrics constructor.
     *
     * @param array                                         $metrics
     * @param PrometheusMetricFactoryInterface              $metricFactory
     * @param PrometheusMetricDescriptorRepositoryInterface $descriptorRepository
     */
    public function __construct(
        array $metrics,
        PrometheusMetricFactoryInterface $metricFactory,
        PrometheusMetricDescriptorRepositoryInterface $descriptorRepository
    ) {
        $this->metricFactory = $metricFactory;
        $this->descriptorRepository = $descriptorRepository;

        /** @var PrometheusMetricDescriptor $descriptor */
        foreach ($descriptorRepository as $name => $descriptor) {
            switch ($descriptor->getType()) {
                case 'counter':
                    $this->counters[$name] = $this->metricFactory->makeCounter($descriptor);
                    break;
                case 'gauge':
                    $this->gauges[$name] = $this->metricFactory->makeGauge($descriptor);
                    break;
                case 'histogram':
                    $buckets = $metrics[$name]['buckets'];
                    $this->histograms[$name] = $this->metricFactory->makeHistogram($descriptor, $buckets);
                    break;
            }
        }
    }

    /**
     * @param string $name
     *
     * @return PrometheusMetricCounterInterface
     */
    public function getCounter(string $name): PrometheusMetricCounterInterface
    {
        return $this->counters[$name];
    }

    /**
     * @param string $name
     *
     * @return PrometheusMetricGaugeInterface
     */
    public function getGauge(string $name): PrometheusMetricGaugeInterface
    {
        return $this->gauges[$name];
    }

    /**
     * @param string $name
     *
     * @return PrometheusMetricHistogramInterface
     */
    public function getHistogram(string $name): PrometheusMetricHistogramInterface
    {
        return $this->histograms[$name];
    }

    // IteratorAggregate methods implementation

    public function getIterator()
    {
        foreach ($this->counters as $name => $metric) {
            yield $name => $metric;
        }

        foreach ($this->gauges as $name => $metric) {
            yield $name => $metric;
        }

        foreach ($this->histograms as $name => $metric) {
            yield $name => $metric;
        }
    }
}
