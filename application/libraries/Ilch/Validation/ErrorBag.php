<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Validation;

/**
 * Validation error class
 * This is just a mapper for a single error
 */
class ErrorBag
{
    /** @var array */
    protected $errors;

    public function __construct()
    {
        $this->errors = [];
    }

    public function getErrorMessages()
    {
        $messages = [];

        foreach ($this->getErrors() as $errors) {
            foreach ($errors as $error) {
                array_push($messages, $error);
            }
        }

        return $messages;
    }

    public function hasErrors()
    {
        return count($this->getErrors()) > 0;
    }

    /**
     * @param string $field
     * @param string $message
     * @return $this
     */
    public function addError($field, $message)
    {
        $this->errors[$field][] = $message;

        return $this;
    }

    /**
     * Get the field names of where errors occurred
     * @return array
     */
    public function getErrorFields()
    {
        return array_keys($this->getErrors());
    }

    /**
     * Checks if a given field has an error
     *
     * @param string $field
     *
     * @return boolean
     */
    public function hasError($field)
    {
        return !empty($this->errors[$field]);
    }

    /**
     * Gets the value of errors.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Sets the value of errors.
     *
     * @param array $errors the errors
     *
     * @return self
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;

        return $this;
    }
}
