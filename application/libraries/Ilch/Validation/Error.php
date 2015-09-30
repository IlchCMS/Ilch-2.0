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
class Error
{
    protected $key;
    protected $params = [];
    protected $field;

    /**
     * Sets all error data
     * @param String $key    An translation key
     * @param Array  $params An array of 2d arrays
     */
    public function __construct($field, $key, $params)
    {
        $this->setKey($key);
        $this->setField($field);

        foreach($params as $param) {
            $parameter = [];

            if(is_array($param)) {
                $parameter['value'] = $param[0];
                $parameter['translate'] = $param[1];
            } else {
                $parameter['value'] = $param;
                $parameter['translate'] = false;
            }

            $this->addParam($parameter);
        }
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function setField($field)
    {
        $this->field = $field;
    }

    public function addParam($param)
    {
        $this->params[] = $param;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getField()
    {
        return $this->field;
    }

    public function getParams()
    {
        return $this->params;
    }
}
