<?php
namespace Ddeboer\GuzzleBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\Config\Definition\Processor;

use Millwright\ConfigurationBundle\ContainerUtil as Util;

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
        $normalizer = function(array &$config, Processor $processor, ContainerBuilder $container)
        {
            $config = $processor->processConfiguration(new CommandConfiguration, array('guzzle' => $config));
        };

        $configs     = Util::collectConfiguration('guzzle_command', $container, $normalizer);
        $definitions = Util::getDefinitionsByTag('guzzle_client', $container);

        foreach ($definitions as $type => $clientDefinition) {
            $commands = $configs[$type];
            $clientDefinition->replaceArgument('commands', $commands);
        }
    }
}
