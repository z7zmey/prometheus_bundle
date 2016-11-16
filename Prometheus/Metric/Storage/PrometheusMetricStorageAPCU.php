<?php

declare(strict_types = 1);

namespace Prometheus\Metric\Storage;

use APCUIterator;
use Prometheus\Metric\Descriptor\PrometheusMetricDescriptorInterface as Descriptor;
use Prometheus\Sample\PrometheusSampleFactoryInterface;

class PrometheusMetricStorageAPCU implements PrometheusMetricStorageInterface
{
    const PREFIX = 'prometheus';
    const DELIMITER = ':';

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
        apcu_add($key, $this->toInteger(0));

        do {
            $old = apcu_fetch($key);
            $done = apcu_cas($key, $old, $this->toInteger($this->fromInteger($old) + $value));
        } while (!$done);
    }

    /**
     * @param Descriptor $metricDescriptor
     * @param            $value
     * @param array      $labelValues
     */
    protected function set(Descriptor $metricDescriptor, $value, array $labelValues = [])
    {
        $key = $this->getStorageKey($metricDescriptor->getName(), $labelValues);
        apcu_store($key, $this->toInteger($value));
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
        foreach (new APCUIterator(sprintf('/^%s/', $key)) as $value) {
            $parts = explode(self::DELIMITER, $value['key']);
            $labelValues = json_decode($parts[2]);

            yield $value['key'] => $this->sampleFactory->make(
                $parts[1],
                $labelValues,
                $this->fromInteger($value['value'])
            );
        }
    }

    /**
     * @param mixed $val
     *
     * @return int
     */
    protected function toInteger($val)
    {
        return unpack('Q', pack('d', $val))[1];
    }

    /**
     * @param mixed $val
     *
     * @return int
     */
    protected function fromInteger($val)
    {
        return unpack('d', pack('Q', $val))[1];
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
        apcu_add($key, $this->toInteger(0));
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
