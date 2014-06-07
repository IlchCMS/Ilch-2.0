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
     * @var array $breakChain Holds fields that should break the chain :P
     */
    protected $breakChain;

    /**
     * @var array $filters Holds all filters
     */
    protected $filters;

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
        if (in_array('breakChain', $rules)) {
            $this->setBreakChain($item, true);
            unset($rules[array_search('breakChain', $rules)]);
        }

        if (isset($rules['filters'])) {
            $this->setFilters($item, $rules['filters']);
            unset($rules['filters']);
        }

        foreach ($rules as $rule => $parameters) {

            if (is_int($rule)) {
                $rule = $parameters;
                $parameters = null;
            }

            $this->rules[$item][$rule] = $parameters;
        }
    }

    /**
     * Validates $data
     *
     * @param array $data
     * @param boolean $bypassFilters If true before and after filters will be ignored
     */
    public function validate($data, $bypassFilters = false)
    {
        $this->data = $data;

        foreach ($this->rules as $item => $rules) {

            // beforeFilters
            $this->filter('before', $item);

            foreach ($rules as $validator => $parameters) {

                if ($this->getBreakChain($item) === true and $this->hasError($item)) {
                    break;
                }

                if (strpos($validator, "\\") === false) {
                    $validator = "\Ilch\Validators\\".ucfirst($validator);
                }

                $validation = new $validator;

                $validation->prepare(array_dot($this->data, $item), $this->data, $parameters);
                $validation->execute();

                if ($validation->hasError()) {
                    $this->addError($item, $validation->getMessage());
                }
            }

            // afterFilters
            $this->filter('after', $item);
        }
    }

    protected function filter($filter_type, $item)
    {
        foreach ($this->getFilters($item, $filter_type) as $filter => $params) {
            if (is_int($filter)) {
                $filter = $params;
                $params = null;
            }

            if (strpos($filter, "\\") === false and strpos($filter, "::") === false) {
                if (class_exists("\Ilch\Filters\\".ucfirst($filter))) {
                    $filter = "\Ilch\Filters\\".ucfirst($filter);
                }
            }

            $filterParts = explode("::", $filter);

            if (class_exists($filter)) {

                $filterInstance = new $filter($params);

                $filtered = $filterInstance->filter(array_dot($this->data, $item));
            } elseif (count($filterParts > 1) and (isset($filterParts[1]) and method_exists($filterParts[0], $filterParts[1]))) {

                $filtered = call_user_func_array($filter, $params);
            } elseif (function_exists($filter)) {
                // Replace placeholder with content
                $params = str_replace(self::SELF, array_dot($this->data, $item), $params);

                $filtered = call_user_func_array($filter, $params);
            } else {
                throw new \RuntimeException('The filter "'.$filter.'" does not exist');
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
        $this->errors[$property][] = $message;
    }

    public function hasError($item)
    {
        return isset($this->errors[$item]);
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

    /**
     * Sets breakChain for a specific item/field
     *
     * @param string $item The field/item to set breakChain for
     * @param boolean $bool true or false
     *
     * @return $this
     */
    protected function setBreakChain($item, $bool)
    {
        $this->breakChain[$item] = $bool;
        return $this;
    }

    /**
     * Gets breakChain for a specific item/field
     *
     * @param string $item
     *
     * @return  boolean
     */
    protected function getBreakChain($item)
    {
        if (isset($this->breakChain[$item])) {
            return $this->breakChain[$item];
        }

        return false;
    }

    /**
     * Sets filters for a item
     *
     * @param string $item
     * @param array $filters
     *
     * @return $this
     */
    protected function setFilters($item, $filters)
    {
        $this->filters[$item] = $filters;
        return $this;
    }

    /**
     * Gets filters for a item
     *
     * @param string $item
     * @param string $filter_type e.g. after, before
     *
     * @return array $filters
     */
    protected function getFilters($item, $filter_type)
    {
        if (isset($this->filters[$item][$filter_type])) {
            return $this->filters[$item][$filter_type];
        }

        return [];
    }
}
