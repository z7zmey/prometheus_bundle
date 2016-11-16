<?php

declare(strict_types = 1);

namespace Prometheus\Metric\Storage;

use Prometheus\Metric\Descriptor\PrometheusMetricDescriptorInterface as Descriptor;
use Prometheus\Sample\PrometheusSampleFactoryInterface;

class prometheusMetricStorageInMemory implements PrometheusMetricStorageInterface
{
    const PREFIX = 'prometheus';
    const DELIMITER = ':';

    /**
     * @var array
     */
    protected $storage = [];

    /**
     * @var PrometheusSampleFactoryInterface
     */
    protected $sampleFactory;

    public function __construct(PrometheusSampleFactoryInterface $sampleFactory)
    {
        $this->sampleFactory = $sampleFactory;
    }

    /**
     * @param string $key
     * @param        $value
     */
    protected function increment(string $key, $value)
    {
        if (!array_key_exists($key, $this->storage)) {
            $this->storage[$key] = 0;
        }

        $this->storage[$key] += $value;
    }

    /**
     * @param Descriptor $metricDescriptor
     * @param            $value
     * @param array      $labelValues
     */
    protected function set(Descriptor $metricDescriptor, $value, array $labelValues = [])
    {
        $key = $this->getStorageKey($metricDescriptor->getName(), $labelValues);
        $this->storage[$key] = $value;
    }

    /**
     * @param string      $name
     * @param array       $labelValues
     * @param string|null $additional
     *
     * @return string
     */
    protected function getStorageKey(string $name, array $labelValues, string $additional = null)
    {
        $parts = [self::PREFIX, $name, json_encode($labelValues), $additional];
        return implode(self::DELIMITER, $parts);
    }

    /**
     * @param string      $name
     * @param string|null $additional
     *
     * @return string
     */
    protected function getRequestKey(string $name, string $additional = null)
    {
        return implode(self::DELIMITER, [self::PREFIX, $name, '.*', $additional]);
    }

    /**
     * @param string $key
     *
     * @return \Generator
     */
    protected function get(string $key): \Generator
    {
        foreach ($this->storage as $index => $value) {
            if (!preg_match(sprintf('/^%s/', $key), $index)) {
                continue;
            }

            $parts = explode(self::DELIMITER, $index);
            $labelValues = json_decode($parts[2]);

            yield $index => $this->sampleFactory->make(
                $parts[1],
                $labelValues,
                $value
            );
        }
    }

    // PrometheusMetricCounterStorageInterface methods implementation

    public function incrementCounter(Descriptor $metricDescriptor, $value, array $labels)
    {
        $key = $this->getStorageKey($metricDescriptor->getName(), $labels);
        $this->increment($key, $value);
    }

    public function getCounter(Descriptor $metricDescriptor): \Generator
    {
        $key = $this->getRequestKey($metricDescriptor->getName());
        yield from $this->get($key);
    }

    // PrometheusMetricGaugeStorageInterface methods implementation

    public function incrementGauge(Descriptor $metricDescriptor, $value, array $labels)
    {
        $key = $this->getStorageKey($metricDescriptor->getName(), $labels);
        $this->increment($key, $value);
    }

    public function setGauge(Descriptor $metricDescriptor, $value, array $labels)
    {
        $this->set($metricDescriptor, $value, $labels);
    }

    public function getGauge(Descriptor $metricDescriptor): \Generator
    {
        $key = $this->getRequestKey($metricDescriptor->getName());
        yield from $this->get($key);
    }

    // PrometheusMetricHistogramStorageInterface methods implementation

    public function incrementHistogramBucket(Descriptor $metricDescriptor, $value, array $labels)
    {
        $key = $this->getStorageKey($metricDescriptor->getName(), $labels, 'bucket');
        $this->increment($key, $value);
    }

    public function addHistogramBucket(Descriptor $metricDescriptor, array $labels)
    {
        $key = $this->getStorageKey($metricDescriptor->getName(), $labels, 'bucket');

        if (!array_key_exists($key, $this->storage)) {
            $this->storage[$key] = 0;
        }
    }

    public function incrementHistogramCount(Descriptor $metricDescriptor, $value, array $labels = [])
    {
        $key = $this->getStorageKey($metricDescriptor->getName(), $labels, 'count');
        $this->increment($key, $value);
    }

    public function incrementHistogramSum(Descriptor $metricDescriptor, $value, array $labels = [])
    {
        $key = $this->getStorageKey($metricDescriptor->getName(), $labels, 'sum');
        $this->increment($key, $value);
    }

    public function incrementHistogramInf(Descriptor $metricDescriptor, $value, array $labels = [])
    {
        $key = $this->getStorageKey($metricDescriptor->getName(), $labels, 'inf');
        $this->increment($key, $value);
    }

    public function getHistogramBuckets(Descriptor $metricDescriptor): \Generator
    {
        $key = $this->getRequestKey($metricDescriptor->getName(), 'bucket');
        yield from $this->get($key);
    }

    public function getHistogramSum(Descriptor $metricDescriptor): \Generator
    {
        $key = $this->getRequestKey($metricDescriptor->getName(), 'sum');
        yield from $this->get($key);
    }

    public function getHistogramCount(Descriptor $metricDescriptor): \Generator
    {
        $key = $this->getRequestKey($metricDescriptor->getName(), 'count');
        yield from $this->get($key);
    }

    public function getHistogramInf(Descriptor $metricDescriptor): \Generator
    {
        $key = $this->getRequestKey($metricDescriptor->getName(), 'inf');
        yield from $this->get($key);
    }
}
