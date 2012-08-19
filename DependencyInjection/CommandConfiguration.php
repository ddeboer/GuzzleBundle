<?php

namespace Ddeboer\GuzzleBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class CommandConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $builder
            ->root('guzzle')
                ->children()
                    ->arrayNode('clients')
                        ->useAttributeAsKey('name')
                        ->prototype('array')
                            ->children()
                                ->scalarNode('name')->end()
                                ->arrayNode('commands')
                                    ->useAttributeAsKey('name')
                                    ->prototype('array')
                                        ->children()
                                            ->scalarNode('name')->end()
                                            ->scalarNode('method')->defaultValue('GET')->end()
                                            ->scalarNode('uri')->end()
                                            ->scalarNode('class')->end()
                                            ->scalarNode('extends')->end()
                                            ->scalarNode('doc')->end()
                                            ->arrayNode('params')
                                                ->useAttributeAsKey('name')
                                                ->prototype('array')
                                                    ->children()
                                                        ->scalarNode('doc')->end()
                                                        ->scalarNode('name')->end()
                                                        ->scalarNode('type')->end()
                                                        ->booleanNode('required')->defaultValue(false)->end()
                                                    ->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $builder;
    }
}
