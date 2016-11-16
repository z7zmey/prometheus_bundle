<?php

declare(strict_types = 1);

namespace Prometheus\Sample;

interface PrometheusSampleInterface
{
    public function addLabelValue(string $labelValue);

    public function setSuffix(string $suffix);

    /**
     * @return string
     */
    public function getSuffix(): string ;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return array
     */
    public function getLabelValues(): array;

    /**
     * @return float|int
     */
    public function getValue();
}
