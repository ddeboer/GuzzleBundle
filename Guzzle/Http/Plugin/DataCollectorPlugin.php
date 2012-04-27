<?php

namespace Ddeboer\GuzzleBundle\Guzzle\Http\Plugin;

use Guzzle\Common\Event;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Plugin\LogPlugin;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * DataCollectorPlugin.
 *
 * @author Gordon Franke <info@nevalon.de>
 */
class DataCollectorPlugin extends LogPlugin implements EventSubscriberInterface
{
    private $messages = array();

    /**
     * Construct a new LogPlugin
     */
    public function __construct()
    {
        $this->hostname = gethostname();
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function addMessage($message)
    {
        return $this->messages[] = $message;
    }

    public function countMessages()
    {
        return count($this->getMessages());
    }

    /**
     * Called before a request is sent
     *
     * @param Event $event
     */
    public function onRequestBeforeSend(Event $event)
    {
        $request = $event['request'];
        // We need to make special handling for content wiring and
        // non-repeatable streams.
        if ($request instanceof EntityEnclosingRequestInterface) {
            if ($request->getBody() && (!$request->getBody()->isSeekable() || !$request->getBody()->isReadable())) {
                // The body of the request cannot be recalled so
                // logging the content of the request will need to
                // be streamed using updates
                $request->getParams()->set('request_wire', EntityBody::factory());
            }
        }
        if (!$request->isResponseBodyRepeatable()) {
            // The body of the response cannot be recalled so
            // logging the content of the response will need to
            // be streamed using updates
            $request->getParams()->set('response_wire', EntityBody::factory());
        }
    }

    /**
     * Triggers the actual log write when a request completes
     *
     * @param Event $event
     */
    public function onRequestComplete(Event $event)
    {
        $this->log($event['request'], $event['response']);
    }

    /**
     * Log a message based on a request and response
     *
     * @param RequestInterface $request Request to log
     * @param Response $response (optional) Response to log
     */
    private function log(RequestInterface $request, Response $response = null)
    {
        $message = array(
            'scheme' => strtoupper($request->getScheme()),
            'host' => $request->getHost(),
            'method' => $request->getMethod(),
            'uri' => $request->getResourceUri(),
            'params' => (array) $request->getQuery()->get('data'),
            'executionMS' => $response->getInfo('total_time')
        );

        $this->addMessage($message);
    }
}