<?php

namespace Ddeboer\GuzzleBundle\Guzzle\Service;

use Guzzle\Service\Exception\ClientNotFoundException;

use Guzzle\Service\ServiceBuilder as BaseServiceBuilder;

/**
 * Service builder to generate service builders and service clients from
 * configuration settings
 */
class ServiceBuilder extends BaseServiceBuilder
{
    private $plugins = array();

    /**
     * {@inheritdoc}
     */
    public function get($name, $throwAway = false)
    {
        if (!isset($this->builderConfig[$name])) {
            throw new ClientNotFoundException('No client is registered as ' . $name);
        }

        if (!$throwAway && isset($this->clients[$name])) {
            return $this->clients[$name];
        }

        $client = parent::get($name, $throwAway);
        foreach ($this->getPlugins() as $plugin) {
            $client->getEventDispatcher()->addSubscriber($plugin);
        }

        return $client;
    }

    public function addPlugin($plugin)
    {
        $this->plugins[] = $plugin;
    }

    public function getPlugins()
    {
        return $this->plugins;
    }
}
