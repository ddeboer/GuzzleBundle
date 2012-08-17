<?php
namespace Ddeboer\GuzzleBundle\Builder;

use Guzzle\Service\Description\ArrayDescriptionBuilder;
use Guzzle\Service\Description\ApiCommand;
use Guzzle\Service\Exception\DescriptionBuilderException;
use Guzzle\Service\Description\ServiceDescription;

class CommandBuilder
{
    public function buildFromArray(array $command)
    {
        if (!isset($command['name'])) {
            throw new \InvalidArgumentException;
        }

        $name = $command['name'];

        // Extend other commands
        if (!empty($command['extends'])) {

            $originalParams = empty($command['params']) ? false: $command['params'];
            $resolvedParams = array();

            foreach ((array) $command['extends'] as $extendedCommand) {
                if (empty($extendedCommand)) {
                    throw new DescriptionBuilderException("{$name} extends missing command {$extendedCommand}");
                }
                $toArray = $extendedCommand->toArray();
                $resolvedParams = empty($resolvedParams) ? $toArray['params'] : array_merge($resolvedParams, $toArray['params']);
                $command = array_merge($toArray, $command);
            }

            $command['params'] = $originalParams ? array_merge($resolvedParams, $originalParams) : $resolvedParams;
        }
        // Use the default class
        $command['class'] = isset($command['class']) ? str_replace('.', '\\', $command['class']) : ServiceDescription::DEFAULT_COMMAND_CLASS;

        return new ApiCommand($command);
    }
}
