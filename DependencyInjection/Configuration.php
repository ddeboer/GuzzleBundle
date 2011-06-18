<?php

namespace Ddeboer\GuzzleBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();
        
        $builder
            ->root('guzzle')
                ->children()
                    ->arrayNode('service_builder')
                        ->children()
                            ->scalarNode('class')
                                ->defaultValue('Guzzle\Service\ServiceBuilder')
                            ->end()
                            ->scalarNode('configuration_file')
                                ->defaultValue('%kernel.root_dir%/config/webservices.xml')
                            ->end()
                            ->arrayNode('cache')
                                ->children()
                                    ->scalarNode('adapter')->end()
                                    ->scalarNode('driver')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        
        return $builder;                    
    }
}