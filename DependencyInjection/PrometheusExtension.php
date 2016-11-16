<?php

declare(strict_types=1);

namespace PrometheusBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class PrometheusExtension extends ConfigurableExtension
{
    /**
     * @inheritDoc
     *
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    protected function loadInternal(array $config, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/Config'));
        $loader->load('services.yml');

        $container->setParameter('prometheus.metrics', $config['metrics']);
    }
}
