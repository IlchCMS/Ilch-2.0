<?php

/**
 * Min Length validation class
 *
 * @copyright Ilch 2.0
 * @package ilch
 * @author Tobias Schwarz <tobias.schwarz@gmx.eu>
 */

namespace Ilch\Validators;

class MinLength extends Base
{
    /**
     * @var mixed The value to validate
     */
    protected $value;

    /**
     * @var integer min length of the value
     */
    protected $min;

    /**
     * Prepares the validator
     *
     * @param mixed   $value       The value to validate
     * @param array   $source      The whole source array
     * @param integer $min         Minimum length
     */
    public function prepare($value, $source, $min)
    {
        $this->value = $value;
        $this->min = $min;
    }

    /**
     * Executes the validation
     */
    public function execute()
    {
        if (strlen($this->value) < $this->min) {
            $this->addError("muss mindestens {$this->min} Zeichen lang sein.");
        }
    }
}
