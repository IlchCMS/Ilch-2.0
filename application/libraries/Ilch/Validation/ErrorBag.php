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
    protected $errors;

    public function __construct()
    {
        $this->errors = [];
    }

    public function getErrorMessages()
    {
        $messages = [];

        foreach (array_values($this->getErrors()) as $errors) {
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

    public function addError($field, $message)
    {
        $this->errors[$field][] = $message;

        return $this;
    }

    public function getErrorFields()
    {
        return array_keys($this->getErrors());
    }

    /**
     * Checks if a given field has an error
     *
     * @return boolean
     */
    public function hasError($field)
    {
        return in_array($field, array_keys($this->getErrors()));
    }

    /**
     * Gets the value of errors.
     *
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Sets the value of errors.
     *
     * @param mixed $errors the errors
     *
     * @return self
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;

        return $this;
    }
}
