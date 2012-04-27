<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ddeboer\GuzzleBundle\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * MessageDataCollector.
 *
 * @author Gordon Franke <info@nevalon.de>
 */
class HttpDataCollector extends DataCollector
{
    private $container;

    /**
     * Constructor.
     *
     * We don't inject the data collector plugin or guzzle here
     * to avoid the creation of these objects when no guzzle requests send.
     *
     * @param ContainerInterface $container A ContainerInterface instance
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        // only collect when Swiftmailer has already been initialized
        if (class_exists('Guzzle\Guzzle', false)) {
            var_dump('guzzle request was send');
            $plugin = $this->container->get('guzzle.plugin.data_collector');
            $this->data['messages']     = $plugin->getMessages();
            $this->data['messageCount'] = $plugin->countMessages();
        } else {
            $this->data['messages']     = array();
            $this->data['messageCount'] = 0;
        }
    }

    public function getMessageCount()
    {
        return $this->data['messageCount'];
    }

    public function getMessages()
    {
        return $this->data['messages'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'guzzle';
    }
}