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

use Guzzle\Common\Log\LogAdapterInterface;

/**
 * MessageDataCollector.
 *
 * @author Gordon Franke <info@nevalon.de>
 */
class HttpDataCollector extends DataCollector
{
    protected $logAdapter;

    /**
     * Constructor.
     *
     * We don't inject the data collector plugin or guzzle here
     * to avoid the creation of these objects when no guzzle requests send.
     *
     * @param LogAdapterInterface $logAdapter The log adapter
     */
    public function __construct($logAdapter)
    {
        $this->logAdapter = $logAdapter;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['logs']     = $this->logAdapter->getLogs();
        $this->data['logCount'] = count($this->data['logs']);
    }

    /**
     * Get the log entries
     *
     * @return array
     */
    public function getLogs()
    {
        return $this->data['logs'];
    }

    /**
     * Hom many log entries became logged
     *
     * @return integer
     */
    public function countLogs()
    {
        return $this->data['logCount'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'guzzle';
    }
}
