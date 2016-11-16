<?php

declare(strict_types=1);

namespace Prometheus\Metric\Descriptor;

class PrometheusMetricDescriptor implements PrometheusMetricDescriptorInterface
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $help;

    /**
     * @var array
     */
    private $labels;

    public function __construct(string $type, string $name, string $help, array $labels = [])
    {
        $this->type = $type;
        $this->name = $name;
        $this->help = $help;
        $this->labels = $labels;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getHelp(): string
    {
        return $this->help;
    }

    /**
     * @return array
     */
    public function getLabels(): array
    {
        return $this->labels;
    }


    public function __toString()
    {
        return $this->name;
    }
}
