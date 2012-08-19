<?php
namespace Ddeboer\GuzzleBundle\Builder;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Ddeboer\GuzzleBundle\Client;
use Guzzle\Service\Description\ServiceDescription;
use Guzzle\Service\Description\ApiCommand;

use Ddeboer\GuzzleBundle\Inspector;

/**
 * Client builder factory
 */
class ClientBuilder
{
    protected $clientClass;
    protected $descriptionClass;

    protected $commands;
    protected $config;
    protected $dispatcher;
    protected $inspector;

    /**
     * Constructor
     *
     * @param string                   $clientClass
     * @param string                   $descriptionClass
     * @param EventDispatcherInterface $dispatcher
     * @param Inspector                $inspector
     * @param ApiCommand[]             $commands
     * @param array                    $config
     */
    public function __construct(
        $clientClass,
        $descriptionClass,
        EventDispatcherInterface $dispatcher,
        Inspector $inspector,
        array $commands,
        array $config = array()
    ) {
        $this->clientClass      = $clientClass;
        $this->descriptionClass = $descriptionClass;
        $this->dispatcher       = $dispatcher;
        $this->inspector        = $inspector;
        $this->commands         = $commands;
        $this->config           = $config;
    }

    /**
     * Create client instance
     *
     * @param string $clientId Client id, needed for commands association
     * @param string $baseUrl  Base url
     * @param string $username User name
     * @param string $password Password
     * @param array  $config   Other config options, optional
     *
     * @return Client
     */
    public function factory($clientId, $baseUrl, $username, $password, array $config = array())
    {
        /** @var $client Client */
        $client = new $this->clientClass($baseUrl, array_merge($this->config, $config, array(
            'username' => $username,
            'password' => $password
        )));
        $client->setEventDispatcher($this->dispatcher);
        $client->setInspector($this->inspector);

        /** @var $serviceDescription ServiceDescription */
        $commands = $this->commands[$clientId];

        $serviceDescription = new $this->descriptionClass($commands);
        $client->setDescription($serviceDescription);

        return $client;
    }
}
