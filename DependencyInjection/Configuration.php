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
                            ->arrayNode('configuration')
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
                    ->booleanNode('logging')->defaultValue($this->debug)->end()
                ->end()
            ->end();

        return $builder;
    }
}
