<?php

/**
 * Max Length validation class
 *
 * @copyright Ilch 2.0
 * @package ilch
 * @author Tobias Schwarz <tobias.schwarz@gmx.eu>
 */

namespace Ilch\Validators;

class MaxLength extends Base
{
    /**
     * @var mixed The value to validate
     */
    protected $value;

    /**
     * @var integer Max length of the value
     */
    protected $max;

    /**
     * Prepares the validator
     *
     * @param mixed   $value       The value to validate
     * @param array   $source      The whole source array
     * @param integer $max         Maximum length
     */
    public function prepare($value, $source, $max)
    {
        $this->value = $value;
        $this->max = $max;
    }

    /**
     * Executes the validation
     */
    public function execute()
    {
        if (strlen($this->value) > $this->max) {
            $this->addError("darf höchstens {$this->max} Zeichen lang sein.");
        }
    }
}
