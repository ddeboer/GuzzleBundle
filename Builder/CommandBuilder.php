<?php
namespace Ddeboer\GuzzleBundle\Builder;

use Guzzle\Service\Description\ArrayDescriptionBuilder;
use Guzzle\Service\Description\ApiCommand;
use Guzzle\Service\Description\ApiCommandInterface;
use Guzzle\Service\Exception\DescriptionBuilderException;
use Guzzle\Service\Description\ServiceDescription;
class CommandBuilder
{
    protected $defaultClass = ServiceDescription::DEFAULT_COMMAND_CLASS;

    /**
     * @param string                $name
     * @param ApiCommandInterface[] $extends
     *
     * @return ApiCommand
     *
     * @throws \Guzzle\Service\Exception\DescriptionBuilderException
     * @throws \InvalidArgumentException
     */
    public function build(
        $name,
        array $command = array(),
        array $params  = array(),
        array $extends = array(),
        $class = null
    ) {
        // Extend other commands
        if ($extends) {
            $resolvedParams = array();

            foreach ($extends as $extendedCommand) {
                if (!$extendedCommand instanceof ApiCommandInterface) {
                    throw new DescriptionBuilderException("{$name} extends missing command {$extendedCommand}");
                }
                $toArray        = $extendedCommand->toArray();
                $resolvedParams = array_merge($resolvedParams, $toArray['params']);
                $command        = array_merge($toArray, $command);
            }

            $params = array_merge($resolvedParams, $params);
        }

        return new ApiCommand(array_merge($command, array(
            'params' => $params,
            'class'  => $class ? str_replace('.', '\\', $class) : $this->defaultClass,
        )));
    }
}
