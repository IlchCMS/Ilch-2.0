<?php

/**
 * Required validation class
 *
 * @copyright Ilch 2.0
 * @package ilch
 * @author Tobias Schwarz <tobias.schwarz@gmx.eu>
 */

namespace Ilch\Validators;

class Required extends Base
{
    /**
     * @var mixed The value to validate
     */
    protected $value;

    /**
     * Prepares the validator
     *
     * @param mixed   $value       The value to validate
     * @param array   $source      The whole source array
     * @param integer $params      Parameters
     */
    public function prepare($value, $source, $params)
    {
        $this->value = $value;
    }

    /**
     * Executes the validation
     */
    public function execute()
    {
        if (empty($this->value) and $this->value !== 0) {
            $this->addError('ist ein Pflichtfeld.');
        }
    }
}
