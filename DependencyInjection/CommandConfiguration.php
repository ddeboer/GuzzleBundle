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
                                            ->arrayNode('params')
                                                ->useAttributeAsKey('params')->prototype('scalar')->end()
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
