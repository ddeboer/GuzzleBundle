<?php
namespace Ddeboer\GuzzleBundle;

use Guzzle\Service\Client as BaseClient;
use Guzzle\Service\Command\AbstractCommand;

/**
 * Client
 */
class Client extends BaseClient
{
    protected $inspector;

    public function setInspector(Inspector $inspector)
    {
        $this->inspector = $inspector;

        return $inspector;
    }
    /**
     * {@inheritDoc}
     */
    public function getCommand($name, array $args = array())
    {
        $command = parent::getCommand($name, $args);
        /** @var $command AbstractCommand */
        $command->setInspector($this->inspector);

        return $command;
    }
}
