<?php

namespace PrometheusBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     *
     * @throws \RuntimeException
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('prometheus');

        $this->addPrometheusSection($rootNode);

        return $treeBuilder;
    }

    protected function addPrometheusSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('metrics')
                    ->prototype('array')
                        ->children()
                            ->enumNode('type')
                                ->values(array('counter', 'gauge', 'histogram'))
                            ->end()
                            ->scalarNode('help')->end()
                            ->arrayNode('labels')
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('buckets')
                                ->prototype('float')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
