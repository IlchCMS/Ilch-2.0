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
     * Placeholder for filters
     */
    const SELF = '{SELF_VALUE}';

    /**
     * @var array $errors All errors occured during validation
     */
    protected $errors = array();

    /**
     * @var boolean $passes Whether or not the validation passed
     */
    protected $passes = true;

    /**
     * @var array $rules All set validation rules
     */
    protected $rules;

    /**
     * @var array $data Holds the data of this validation (including changes done through filters!)
     */
    protected $data;

    /**
     * Adds the validation rules
     *
     * @param array $rules
     *
     * @return $this
     */
    public function addRules($rules)
    {
        foreach ($rules as $item => $itemRules) {
            $this->addRule($item, $itemRules);
        }
        return $this;
    }

    /**
     * Adds a validation rule
     *
     * @param string $item
     * @param array $rules
     *
     * @return $this
     */
    protected function addRule($item, $rules)
    {
        foreach ($rules as $rule => $parameters) {
            if (is_int($rule)) {
                $this->rules[$item][] = ['validator' => $parameters, 'parameters' => ''];
            } else {
                $this->rules[$item][] = ['validator' => $rule, 'parameters' => $parameters];
            }
        }
    }

    /**
     * Validates $data
     *
     * @param array $data
     * @param boolean $bypassFilters If true before and after filters will be ignored
     *
     * @return \Ilch\Validator
     */
    public function validate($data, $bypassFilters = false)
    {
        $this->data = $data;

        foreach ($this->rules as $item => $rules) {
            foreach ($rules as $rule) {

                $validator = $rule['validator'];
                $parameters = $rule['parameters'];

                if (strpos($validator, "\\") === false) {
                    $validator = "\Ilch\Validators\\".ucfirst($validator);
                }

                $validation = new $validator;


                // Run beforeFilters
                if (!$bypassFilters) {
                    if (isset($parameters['beforeFilters'])) {
                        $this->filter($parameters['beforeFilters'], $item);
                    }
                }

                $validation->prepare(array_dot($this->data, $item), $this->data, $parameters);
                $validation->execute();

                // Run afterFilters
                if (!$bypassFilters) {
                    if (isset($parameters['afterFilters'])) {
                        $this->filter($parameters['afterFilters'], $item);
                    }
                }

                if ($validation->hasError()) {
                    $this->addError($item, $validation->getMessage());

                    if (isset($parameters['breakChain']) and $parameters['breakChain'] === true) {
                        break 2;
                    }
                }
            }
        }
    }

    protected function filter($filterArray, $item)
    {
        foreach ($filterArray as $filter => $params) {
            if (is_int($filter)) {
                $filter = $params;
                $params = null;
            }

            $filter_orig = $filter;

            if (strpos($filter, "\\") === false) {
                $filter = "\Ilch\Filters\\".ucfirst($filter);
            }

            if (class_exists($filter)) {
                if ($params === null) {
                    $filterInstance = new $filter($params);
                } else {
                    $filterInstance = new $filter;
                }

                $filtered = $filterInstance->filter(array_dot($this->data, $item));
            } elseif (function_exists($filter_orig)) {

                // Replace placeholder with content
                $params = str_replace(self::SELF, array_dot($this->data, $item), $params);

                $filtered = call_user_func_array($filter_orig, $params);
            } else {
                throw new \RuntimeException('The filter "'.$filter_orig.'" does not exist');
            }

            array_dot_set($this->data, $item, $filtered);
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
            $this->errors[] = ['property' => $property, 'message' => $message];
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
     * Returns the data passed to the validate method
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Returns whether or not the validation has passed
     */
    public function passes()
    {
        return $this->passes;
    }
}
