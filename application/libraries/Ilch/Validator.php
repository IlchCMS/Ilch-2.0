<?php

/**
 * Validator class
 *
 * @copyright Ilch 2.0
 * @package ilch
 * @author Tobias Schwarz <tobias.schwarz@gmx.eu>
 */

namespace Ilch;

defined('ACCESS') or die('no direct access');

class Validator
{
    /**
     * @var array $errors All errors occured during validation
     */
    protected $errors = array();

    /**
     * @var boolean $passes Whether or not the validation passed
     */
    protected $passes = true;

    /**
     * Validates $source against $items
     *
     * @param array $source
     * @param array $items
     *
     * @return \Ilch\Validator
     */
    public function check($source, $items = array())
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $parameters) {

                // Checks whether the validator needs parameters or not (e.g. required does not need any parameters)
                if (is_int($rule)) {
                    $className = $parameters;
                    $parameters = null;
                } else {
                    $className = $rule;
                }

                $class = explode('.', $className);

                if (count($class) === 1) {
                    $validator = "\\Ilch\\Validators\\".ucfirst($class[0]);
                } else {
                    $validator = "\\".implode('\\', array_map('ucfirst', $class));
                }

                $validation = new $validator;

                $validation->prepare(array_dot($source, $item), $source, $parameters);
                $validation->execute();

                if ($validation->hasError()) {
                    $this->addError($item, $validation->getMessage());
                }
            }
        }
    }

    /**
     * Adds a new error
     * Automatically sets $this->passes to false
     *
     * @param string $property  The property with the error
     * @param string $message   The error message
     */
    protected function addError($property, $message)
    {
        $this->passes = false;

        if (!array_key_exists($property, $this->errors) && !empty($message)) {
            $this->errors[$property] = $message;
        }
    }

    /**
     * Returns all errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Returns all fields with errors
     *
     * @return array
     */
    public function getFieldsWithErrors()
    {
        return array_keys($this->errors);
    }

    /**
     * Returns whether or not the validation has passed
     */
    public function passes()
    {
        return $this->passes;
    }
}
