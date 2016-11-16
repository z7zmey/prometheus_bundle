<?php

declare(strict_types=1);

namespace Prometheus\Sample;

interface PrometheusSampleFactoryInterface
{
    public function make(string $name, array $labelValues, $value);
}
