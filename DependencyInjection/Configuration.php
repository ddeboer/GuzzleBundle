<?php

namespace Ddeboer\GuzzleBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    private $debug;

    /**
     * Constructor.
     *
     * @param Boolean $debug The kernel.debug value
     */
    public function __construct($debug)
    {
        $this->debug = (Boolean) $debug;
    }

    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();
        
        $builder
            ->root('guzzle')
                ->children()
                    ->arrayNode('service_builder')
                        ->children()
                            ->scalarNode('class')
                                ->defaultValue('Guzzle\Service\Builder\ServiceBuilder')
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
                    ->booleanNode('logging')->defaultValue($this->debug)->end()
                ->end()
            ->end();
        
        return $builder;                    
    }
}
