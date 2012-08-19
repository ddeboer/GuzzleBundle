<?php
namespace Ddeboer\GuzzleBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\Config\Definition\Processor;

use Millwright\ConfigurationBundle\ContainerUtil as Util;
use Millwright\ConfigurationBundle\PhpUtil;

use Ddeboer\GuzzleBundle\DependencyInjection\CommandConfiguration;
use Guzzle\Service\Inspector;

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
        $definitions = Util::getDefinitionsByTag('guzzle_type', $container);

        $inspector = $container->getDefinition('guzzle.inspector');

        foreach ($definitions as $name => $definition) {
            $properties = $definition->getProperties();
            $inspector->addMethodCall('setConstraint', array(
                $name,
                $definition,
                $properties
            ));
        }

        $definitionGroups = Util::getDefinitionsByTag('guzzle_command', $container, true);

        $result = array();
        foreach ($definitionGroups as $clientId => $definitions) {
            foreach ($definitions as $definition) {
                $args = $definition->getArguments();
                $result[$clientId][$args['name']] = $definition;
            }
        }

        $container->getDefinition('guzzle.client_builder')->replaceArgument(4, $result);
    }
}
