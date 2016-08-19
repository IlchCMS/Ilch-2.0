<?php
/**
 * @copyright Ilch 2.0
 */

namespace Ilch\Validation\Validators;

/**
 * Base validation class
 * Checks a string for a minimum and/or maximum length.
 */
class Base
{
    protected $errorKey;
    protected $data;
    protected $value;
    protected $params;
    protected $fieldAliases;
    protected $minParams = null;
    protected $maxParams = null;

    public function __construct(\Ilch\Validation\Data $data, $fieldAliases)
    {
        $this->data = $data;
        $this->value = $data->getValue();
        $this->params = $data->getParams();
        $this->fieldAliases = $fieldAliases;

        if ((!is_null($this->minParams) && count($this->params) < $this->minParams) ||
            (!is_null($this->maxParams) && count($this->params) > $this->maxParams)) {
            throw new \Exception(get_class($this).' expects at least '.$this->minParams.' parameter(s) (maximum is '.$this->maxParams.'), given: '.count($this->params));
        }
    }

    protected function getErrorKey(\Ilch\Validation\Data $data)
    {
        if ($data->getParam('customErrorAlias') !== null) {
            return $data->getParam('customErrorAlias');
        }

        return $this->errorKey;
    }
}
