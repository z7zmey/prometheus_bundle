<?php

declare(strict_types = 1);

namespace Prometheus\Sample;

class PrometheusSample implements PrometheusSampleInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $suffix;

    /**
     * @var array
     */
    protected $labelValues;

    /**
     * @var integer|float
     */
    protected $value;

    public function __construct(string $name, array $labelValues, $value, string $suffix = '')
    {
        $this->name = $name;
        $this->labelValues = $labelValues;
        $this->value = $value;
        $this->suffix = $suffix;
    }

    public function addLabelValue(string $labelValue)
    {
        $this->labelValues[] = $labelValue;
    }

    public function setSuffix(string $suffix)
    {
        $this->suffix = $suffix;
    }

    /**
     * @return string
     */
    public function getSuffix(): string
    {
        return $this->suffix;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getLabelValues(): array
    {
        return $this->labelValues;
    }

    /**
     * @return float|int
     */
    public function getValue()
    {
        return $this->value;
    }
}
