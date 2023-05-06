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

    /**
     * Get error messages.
     *
     * @return array
     */
    public function getErrorMessages(): array
    {
        $messages = [];

        foreach ($this->getErrors() as $errors) {
            foreach ($errors as $error) {
                $messages[] = $error;
            }
        }

        return $messages;
    }

    /**
     * Check if field has an error.
     *
     * @return bool
     */
    public function hasErrors(): bool
    {
        return count($this->getErrors()) > 0;
    }

    /**
     * @param string $field
     * @param string $message
     * @return $this
     */
    public function addError(string $field, string $message): ErrorBag
    {
        $this->errors[$field][] = $message;

        return $this;
    }

    /**
     * Get the field names of where errors occurred
     * @return array
     */
    public function getErrorFields(): array
    {
        return array_keys($this->getErrors());
    }

    /**
     * Checks if a given field has an error
     *
     * @param string $field
     *
     * @return bool
     */
    public function hasError(string $field): bool
    {
        return !empty($this->errors[$field]);
    }

    /**
     * Gets the value of errors.
     *
     * @return array
     */
    public function getErrors(): array
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
    public function setErrors(array $errors): ErrorBag
    {
        $this->errors = $errors;

        return $this;
    }
}
