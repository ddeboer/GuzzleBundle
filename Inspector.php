<?php

namespace Ddeboer\GuzzleBundle;

use Guzzle\Common\Collection;
use Guzzle\Common\Exception\InvalidArgumentException;
use Guzzle\Common\Validation\ConstraintInterface;
use Guzzle\Service\Exception\ValidationException;

use Guzzle\Service\Inspector as BaseInspector;

/**
 * Prepares configuration settings with default values and ensures that required
 * values are set.  Holds references to validation constraints and their default
 * values.
 */
class Inspector extends BaseInspector
{
    /**
     * @var ConstraintInterface[]
     */
    protected $constraints = array();

    protected $constraintArgs = array();

    /**
     * Constructor to create a new Inspector
     */
    public function __construct()
    {
    }

    /**
     * Get an instance of the Inspector
     *
     * @return Inspector
     */
    public static function getInstance()
    {
        throw new \ErrorException('Deprecated');
    }

    public static function prepareConfig(array $config = null, array $defaults = null, array $required = null)
    {
        throw new \ErrorException('Deprecated');
    }

    /**
     * Register a constraint class with a special name
     *
     * @param string $name    Name of the constraint to register
     * @param ConstraintInterface $constraint
     * @param array               $args Constraint validationg args
     *
     * @return Inspector
     */
    public function setConstraint($name, ConstraintInterface $constraint, array $args = null)
    {
        $this->constraints[$name]    = $constraint;
        $this->constraintArgs[$name] = $args;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getConstraint($name)
    {
        if (!isset($this->constraints[$name])) {
            throw new InvalidArgumentException($name . ' has not been registered');
        }

        return $this->constraints[$name];
    }

    /**
     * {@inheritDoc}
     */
    public function validateConstraint($name, $value, array $args = null)
    {
        if (!$args) {
            $args = isset($this->constraintArgs[$name]) ? $this->constraintArgs[$name] : array();
        }

        return $this->getConstraint($name)->validate($value, $args);
    }
}
