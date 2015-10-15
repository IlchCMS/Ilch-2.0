<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Validation;

/**
 * Validation data class
 * This is just a mapper for the data a validations receives
 */
class Data
{
    protected $field;
    protected $input;
    protected $params;
    protected $value;

    public function __construct($field, $value, $params, $input)
    {
        $this->setField($field);
        $this->setValue($value);
        $this->setInput($input);
        $this->setParams($params);
    }

    public function setField($field)
    {
        $this->field = $field;
    }

    public function setInput($input)
    {
        $this->input = $input;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getField()
    {
        return $this->field;
    }

    public function getInput()
    {
        return $this->input;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getParam($name)
    {
        if (isset($this->getParams()[$name])) {
            return $this->getParams()[$name];
        } else {
            return null;
        }
    }
}
