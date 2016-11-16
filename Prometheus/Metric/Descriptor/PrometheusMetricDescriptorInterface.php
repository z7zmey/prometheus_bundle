<?php

declare(strict_types = 1);

namespace Prometheus\Metric\Descriptor;

interface PrometheusMetricDescriptorInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getHelp(): string;

    /**
     * @return array
     */
    public function getLabels(): array;

    public function __toString();
}
