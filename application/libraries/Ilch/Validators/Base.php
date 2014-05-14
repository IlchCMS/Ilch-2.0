<?php

/**
 * Base validation class
 *
 * @copyright Ilch 2.0
 * @package ilch
 * @author Tobias Schwarz <tobias.schwarz@gmx.eu>
 */

namespace Ilch\Validators;

abstract class Base
{
    /**
     * @var boolean $error Whether or not the validator has an error
     */
    protected $error = false;

    /**
     * @var string $message The error message
     */
    protected $message;

    /**
     * Checks if the validator has an error
     *
     * @return boolean
     */
    public function hasError()
    {
        return $this->error;
    }

    /**
     * Returns the error message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets the error message
     *
     * @param string $message
     */
    protected function addError($message)
    {
        $this->error = true;
        $this->message = $message;
    }

    /**
     * Sets error to true without message
     */
    public function silentError()
    {
        $this->error = true;
    }

    abstract public function execute();
    abstract public function prepare($value, $source, $parameters);
}
