<?php

/**
 * Base validation class
 *
 * Checks a string for a minimum and/or maximum length
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Validation\Validators;

class Base
{
    protected $errorKey;
    protected $data;
    protected $fieldAliases;

    public function __construct(\Ilch\Validation\Data $data, $fieldAliases)
    {
        $this->data = $data;
        $this->fieldAliases = $fieldAliases;
    }

    protected function getErrorKey(\Ilch\Validation\Data $data)
    {
        if($data->getParam('customErrorAlias') !== null) {
            return $data->getParam('customErrorAlias');
        }

        return $this->errorKey;
    }
}
