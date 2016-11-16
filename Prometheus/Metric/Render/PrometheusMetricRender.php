<?php

declare(strict_types = 1);

namespace Prometheus\Metric\Render;

use Prometheus\Metric\Descriptor\PrometheusMetricDescriptorInterface;
use Prometheus\Metric\Storage\PrometheusMetricStorageInterface;
use Prometheus\Metric\Type\Counter\PrometheusMetricCounterInterface;
use Prometheus\Metric\Type\Gauge\PrometheusMetricGaugeInterface;
use Prometheus\Metric\Type\Histogram\PrometheusMetricHistogramInterface;
use Prometheus\Sample\PrometheusSampleInterface;

class PrometheusMetricRender implements PrometheusMetricRenderInterface
{
    /**
     * @var PrometheusMetricStorageInterface
     */
    protected $storage;

    public function __construct(PrometheusMetricStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function renderCounter(PrometheusMetricCounterInterface $metric): string
    {
        $descriptor = $metric->getDescriptor();
        $string = $this->renderMeta($descriptor);

        $samples = $this->storage->getCounter($descriptor);
        foreach ($samples as $sample) {
            $string .= $this->renderStr($descriptor->getLabels(), $sample) . PHP_EOL;
        }

        return $string . PHP_EOL;
    }

    public function renderGauge(PrometheusMetricGaugeInterface $metric): string
    {
        $descriptor = $metric->getDescriptor();
        $string = $this->renderMeta($descriptor);

        $samples = $this->storage->getGauge($descriptor);
        foreach ($samples as $sample) {
            $string .= $this->renderStr($descriptor->getLabels(), $sample) . PHP_EOL;
        }

        return $string . PHP_EOL;
    }

    public function renderHistogram(PrometheusMetricHistogramInterface $metric): string
    {
        $descriptor = $metric->getDescriptor();
        $result = $this->renderMeta($descriptor);

        foreach ($this->getSamples($descriptor) as $sample) {
            $result .= $this->renderStr($this->getLabels($sample, $descriptor), $sample) . PHP_EOL;
        }

        return $result . PHP_EOL;
    }

    private function getLabels(PrometheusSampleInterface $sample, PrometheusMetricDescriptorInterface $descriptor)
    {
        if ('_bucket' === $sample->getSuffix()) {
            return array_merge($descriptor->getLabels(), ['le']);
        }

        return $descriptor->getLabels();
    }

    /**
     * @param PrometheusMetricDescriptorInterface $descriptor
     *
     * @return \Generator
     * @internal param PrometheusSampleInterface $sample
     */
    private function getSamples(PrometheusMetricDescriptorInterface $descriptor): \Generator
    {
        $data = [];

        // get buckets

        $buckets = $this->storage->getHistogramBuckets($descriptor);
        /** @var PrometheusSampleInterface $sample */
        foreach ($buckets as $sample) {
            $sample->setSuffix('_bucket');

            $labelValues = $sample->getLabelValues();
            $bucket = (string)end($labelValues);
            $k = json_encode(array_slice($labelValues, 0, -1));
            $data[$k][$bucket] = $sample;
        }

        // sort buckets

        foreach ($data as $k => $v) {
            ksort($data[$k]);
        }

        // get +Inf buckets
        $inf = $this->storage->getHistogramInf($descriptor);
        /** @var PrometheusSampleInterface $sample */
        foreach ($inf as $sample) {
            $k = json_encode($sample->getLabelValues());

            $sample->setSuffix('_bucket');
            $sample->addLabelValue('+Inf');

            $data[$k]['+Inf'] = $sample;
        }

        // get sum

        $sum = $this->storage->getHistogramSum($descriptor);
        /** @var PrometheusSampleInterface $sample */
        foreach ($sum as $sample) {
            $sample->setSuffix('_sum');
            $k = json_encode($sample->getLabelValues());
            $data[$k]['sum'] = $sample;
        }

        // get count

        $count = $this->storage->getHistogramCount($descriptor);
        /** @var PrometheusSampleInterface $sample */
        foreach ($count as $sample) {
            $sample->setSuffix('_count');
            $k = json_encode($sample->getLabelValues());
            $data[$k]['count'] = $sample;
        }

        // return

        foreach ($data as $values) {
            foreach ((array)$values as $sample) {
                yield $sample;
            }
        }
    }

    /**
     * @param PrometheusMetricDescriptorInterface $descriptor
     *
     * @return string
     */
    protected function renderMeta(PrometheusMetricDescriptorInterface $descriptor): string
    {
        $string = '';

        $name = $descriptor->getName();
        $help = $descriptor->getHelp();
        $type = $descriptor->getType();

        $string .= "# HELP {$name} {$help}" . PHP_EOL;
        $string .= "# TYPE {$name} {$type}" . PHP_EOL;

        return $string;
    }

    /**
     * @param array                     $labels
     * @param PrometheusSampleInterface $sample
     *
     * @return string
     */
    protected function renderStr(array $labels, PrometheusSampleInterface $sample)
    {
        $labelsStr = $this->prepareLabels($labels, $sample->getLabelValues());

        return sprintf('%s%s %s', $sample->getName().$sample->getSuffix(), $labelsStr, $sample->getValue());
    }

    /**
     * @param array $labels
     * @param array $values
     *
     * @return string
     */
    private function prepareLabels(array $labels, array $values): string
    {
        $labels = array_combine($labels, $values);

        $escapedLabels = [];
        foreach ($labels as $labelName => $labelValue) {
            $labelValue = $this->escapeLabelValue((string)$labelValue);
            $escapedLabels[] = sprintf('%s="%s"', $labelName, $labelValue);
        }

        if (empty($escapedLabels)) {
            return '';
        }

        return sprintf('{%s}', implode(',', $escapedLabels));
    }

    /**
     * @param string $v
     *
     * @return string
     */
    private function escapeLabelValue(string $v) : string
    {
        return str_replace(["\\", "\n", "\""], ["\\\\", "\\n", "\\\""], $v);
    }
}
