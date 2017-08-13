<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

/**
 * Class Event for a generic event.
 */
class Event
{
    /**
     * Event name.
     *
     * @var string name of the event
     */
    protected $name;

    /**
     * Array of arguments.
     *
     * @var array
     */
    protected $arguments;

    /**
     * Encapsulate an event with $name and $arguments.
     *
     * @param string $name The name of the event, usually an object
     * @param array $arguments Arguments to store in the event
     */
    public function __construct($name = null, array $arguments = array())
    {
        $this->name = $name;
        $this->arguments = $arguments;
    }

    /**
     * Getter for name property.
     *
     * @return string name of the event
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get argument by key.
     *
     * @param string $key Key
     *
     * @return mixed Contents of array key
     *
     * @throws \InvalidArgumentException If key is not found.
     */
    public function getArgument($key)
    {
        if ($this->hasArgument($key)) {
            return $this->arguments[$key];
        }
        throw new \InvalidArgumentException(sprintf('Argument "%s" not found.', $key));
    }

    /**
     * Add argument to event.
     *
     * @param string $key   Argument name
     * @param mixed  $value Value
     *
     * @return $this
     */
    public function setArgument($key, $value)
    {
        $this->arguments[$key] = $value;
        return $this;
    }

    /**
     * Getter for all arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Set arguments property.
     *
     * @param array $arguments Arguments
     *
     * @return $this
     */
    public function setArguments(array $arguments = array())
    {
        $this->arguments = $arguments;
        return $this;
    }

    /**
     * Has argument.
     *
     * @param string $key Key of arguments array
     *
     * @return bool
     */
    public function hasArgument($key)
    {
        return array_key_exists($key, $this->arguments);
    }
}
