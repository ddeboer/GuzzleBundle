<?php

namespace Ddeboer\GuzzleBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
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
        $loader->load('validators.xml');

        $processor = new Processor();
        $configuration = new Configuration($container->getParameter('kernel.debug'));
        $config = $processor->processConfiguration($configuration, $configs);

        if (isset($config['service_builder'])) {
            $container->setParameter('guzzle.service_builder.configuration',
                $config['service_builder']['configuration']);

            if (empty($config['service_builder']['configuration'])) {
                $container->setParameter('guzzle.service_builder.configuration',
                    $config['service_builder']['configuration_file']);
            }
        }

        $clients = isset($config['clients']) ? $config['clients'] : array();

        $container->setParameter('guzzle.clients', $clients);

        if ($config['logging']) {
            $container->findDefinition('guzzle.data_collector')
                ->addTag('data_collector', array('template' => 'DdeboerGuzzleBundle:Collector:guzzle', 'id' => 'guzzle'));
        }
    }
}
