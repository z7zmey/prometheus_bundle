<?php

declare(strict_types = 1);

namespace Prometheus\Sample;

class PrometheusSampleFactory implements PrometheusSampleFactoryInterface
{
    public function make(string $name, array $labelValues, $value): PrometheusSampleInterface
    {
        return new PrometheusSample($name, $labelValues, $value);
    }
}
