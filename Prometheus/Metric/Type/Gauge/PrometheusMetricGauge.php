<?php

declare(strict_types = 1);

namespace Prometheus\Metric\Type\Gauge;

use Prometheus\Metric\PrometheusAbstractMetric;


class PrometheusMetricGauge extends PrometheusAbstractMetric implements PrometheusMetricGaugeInterface
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

        $this->storage->incrementGauge($this->descriptor, $value, $labels);
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

    /**
     * @param float $value
     * @param array $labels
     *
     * @throws \InvalidArgumentException
     */
    public function set(float $value, array $labels = [])
    {
        if (count($this->descriptor->getLabels()) !== count($labels)) {
            throw new \InvalidArgumentException('Wrong labels count');
        }

        $this->storage->setGauge($this->descriptor, $value, $labels);
    }

    public function render(): string
    {
        return $this->render->renderGauge($this);
    }
}
