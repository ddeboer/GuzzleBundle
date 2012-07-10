<?php

namespace Ddeboer\GuzzleBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\Definition\Processor;

class DdeboerGuzzleExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.xml');
        
        $processor = new Processor();
        $configuration = new Configuration($container->getParameter('kernel.debug'));
        $config = $processor->processConfiguration($configuration, $configs);
     
        $container->setParameter('guzzle.service_builder.configuration_file',
                $config['service_builder']['configuration_file']);

        if ($config['logging']) {
            $container->findDefinition('guzzle.data_collector')->addTag('data_collector', array('template' => 'DdeboerGuzzleBundle:Collector:guzzle', 'id' => 'guzzle'));
        }

        if (isset($config['service_builder']['cache'])) {
            $this->loadCacheAdapter($config['service_builder']['cache'], $container);
        }
    }
    
    protected function loadCacheAdapter($config, ContainerBuilder $container)
    {
        if (isset($config['adapter'])) {
            switch ($config['adapter']) {
                case 'doctrine':
                    switch ($config['driver']) {
                        case 'apc':
                        case 'array':
                        case 'xcache':
                            $cacheDefinition = new Definition(
                                '%'.sprintf('guzzle.cache.driver.%s.class', $config['driver']).'%');
                            $cacheDefinition->setPublic(false);
                            $container->setDefinition('guzzle.cache.class', $cacheDefinition);
                           
                            break;

                        default:
                            break;
                    }
                case 'zend':
                    $adapterDefinition = new Definition(
                        '%'.sprintf('guzzle.cache.adapter.%s.class', $config['adapter']).'%');
                    $adapterDefinition->addArgument(new Reference('guzzle.cache.class'));

                default:
                    break;
            }
            $adapterDefinition->setPublic(false);
            $container->setDefinition('guzzle.cache.adapter', $adapterDefinition);
        }
    }
}
