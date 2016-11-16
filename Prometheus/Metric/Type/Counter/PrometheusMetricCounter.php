<?php

declare(strict_types = 1);

namespace Prometheus\Metric\Type\Counter;

use Prometheus\Metric\PrometheusAbstractMetric;


class PrometheusMetricCounter extends PrometheusAbstractMetric implements PrometheusMetricCounterInterface
{
    /**
     * @param float $value
     * @param array $labels
     *
     * @throws \InvalidArgumentException
     */
    public function incBy(float $value, array $labels = [])
    {
        if (count($this->descriptor->getLabels()) !== count($labels)) {
            throw new \InvalidArgumentException('Wrong labels count');
        }

        $this->storage->incrementCounter($this->descriptor, $value, $labels);
    }

    /**
     * @param array $labels
     *
     * @throws \InvalidArgumentException
     */
    public function inc(array $labels = [])
    {
        $this->incBy(1, $labels);
    }

    public function render(): string
    {
        return $this->render->renderCounter($this);
    }
}
