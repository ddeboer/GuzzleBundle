<?php
namespace Ddeboer\GuzzleBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\Config\Definition\Processor;

use Millwright\ConfigurationBundle\ContainerUtil as Util;
use Millwright\ConfigurationBundle\PhpUtil;

use Ddeboer\GuzzleBundle\DependencyInjection\CommandConfiguration;

/**
 * FormCompilerPass
 */
class GrabCommandsPass implements CompilerPassInterface
{
    /**
     * Process
     *
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        $definitionGroups = Util::getDefinitionsByTag('guzzle_command', $container, true);

        $result = array();
        foreach ($definitionGroups as $clientId => $definitions) {
            foreach ($definitions as $definition) {
                $args = $definition->getArguments();
                $result[$clientId][$args[0]['name']] = $definition;
            }
        }


        return $container->getDefinition('guzzle.client_builder')->replaceArgument(2, $result);

        /*
        $normalizer = function(array &$config, Processor $processor, ContainerBuilder $container)
        {
            $conf = array('guzzle' => array('clients' => $config));
            $config = $processor->processConfiguration(new CommandConfiguration, $conf);
        };


        $configs = Util::collectConfiguration('guzzle_command', $container, $normalizer);

        $appConfigs = $container->getParameter('guzzle.clients');
        if (!$appConfigs) {
            $appConfigs = array();
        }

        $config = PhpUtil::merge($configs, $appConfigs);

        $container->getDefinition('guzzle.client_builder')
            ->replaceArgument(2, $config['clients']);
        */
    }
}
